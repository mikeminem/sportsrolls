<?php

namespace App\Http\Controllers;

use App\Pictures;
use App\Tournaments;
use Intervention\Image\Facades\Image;




//use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

class PicturesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
         if($request->ajax()){

            $rows = [];

            $rows = DB::table('pictures as p')
                ->select(
                    'p.id',
                    'p.title',
                    'p.description',
                    'p.photo',
                    'p.status'

                )
                ->where('p.is_deleted', 0)
                ->where('p.user_id',Auth::user()->id)
                ->get();

            return DataTables::of($rows)
                    ->addColumn('title', function($row) {
                        return '<b>' . $row->title . '</b><br>'. $row->description;
                    })
                    ->addColumn('photo', function($row) {
                        $photo = '<img class="img" src="'. asset('images/imgNoPicture500x500.jpg') . '" style="width:100px">';
                        if(!is_null($row->photo) && !empty($row->photo))
                             $photo = '<img src="' . asset('photos/' . $row->photo) . '" style="height:100px">';

                        return $photo;
                    })
                    ->addColumn('status', function($row) {
                        $status = '';
                        if ($row->status == 'UNLOCKED') {
                            $status = '<div class="alert alert-success">
                                <strong>DESBLOQUEADA</strong>
                                </div>';
                        } else {
                            $status = '<div class="alert alert-danger">
                                <strong>BLOQUEADA</strong>
                                </div>';
                        }
                        return  $status;
                    })

                    ->addColumn('action', function($row) {

                        $routeEdit = route('pictures.show', $row->id);
                        $routeDelete = route('pictures.destroy', $row->id);

                        $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Fotografía!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Fotografía" data-name="'.$row->title.'" id="btnDelete">
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns(['photo', 'title',  'status', 'action'])
                    ->make(true);
        }

        $tournaments = Tournaments::where('is_deleted','0')
        ->where('status','!=','Terminado')
        ->where('user_id',Auth::user()->id)
        ->get(['id','title']);



        return view ('pictures.index', [
            'tournaments' => $tournaments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title'         => 'required',
            //'photo'         => 'required',
            'tournament_id' => 'required',
            'journey_id'    => 'required',
        ];

        $attributes = [
            'title'         => 'Título',
            'photo'         => 'Archivo de la Fotografía',
            'tournament_id' => 'Torneo',
            'journey_id'    => 'Jornada '
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);

        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Hay un error al intentar guardar la información del jugador!',
                'errors' => $validated->errors()
            ]);
        }

        $pathphoto = '';
        if($request->file('photo')) {
            //$pathphoto =  date('YmdHis') . '.' .$request->file('photo')->extension();
            //$request->file('photo')->storeAs('public/photos', $pathphoto);

            $image  = $request->file('photo');
            $tmp_image = Image::make($image->getRealPath());

            $tmp_image->resize(800,600);
            $tmp_image->save($image);

            $image->storeAs('/public/photos/', $pathphoto);
        }

        if (!isset($request->id) || empty($request->id)) {
            //insert
            $model          = new Pictures($request->all());
            $model->slug = str_slug($model->title);
            $model->user_id = Auth::user()->id;

            if (!empty($pathphoto)) {
                $model->photo = $pathphoto;
            }
            // try {
            //     $model->save();
            // } catch (Exception $e) {
            //     echo $e->getMessage();
            //     die();
            // }

            if ($model->save())
                 return response()->json(['type' => 'success',
                                 'message' => 'Fotografía guardada!']);
        } else {
            //update
            $model = Pictures::find($request->id);
            $lastphoto = $model->photo;
            $model->user_id = Auth::user()->id;

            $model->fill($request->all());
            $model->slug = str_slug($model->title);


            if (!empty($pathphoto)) {
                Storage::delete('public/photos/' . $lastphoto);
                $model->photo = $pathphoto;
            }

            if ($model->update())
                return response()->json(['type' => 'success',
                                 'message' => 'Fotografía actualizada!']);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la fotografía!'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int
     * @return boolean
     */
    public function show( $id)
    {
        //
        if ($id) {
            $row = Pictures::select(
                'id',
                'title',
                'status',
                'is_deleted',
                'description',
                'photo',
                'tournament_id',
                'journey_id',
                'game_id',
                'user_id',
                'photo'
            )->find($id);
            return $row;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        if ( isset($id)
            && $id !== 0
        ) {
            Pictures::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'Fotografía borrada!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Hubo un error al intentar borrar la fotografía!'
        ]);
    }

}
