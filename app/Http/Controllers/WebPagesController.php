<?php

namespace App\Http\Controllers;

use App\PageCategory;
use App\WebPage;
use App\Tournaments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


use DataTables;
use DB;

class WebPagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['showWebPage']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){

            $rows = [];

                $rows = DB::table('webpages as s')
                    ->select('s.id', 's.title', 's.type', 's.status', 's.description', 's.is_deleted', 's.budget')
                    ->where('s.is_deleted', 0)
                    //->where('s.user_id',Auth::user()->id)
                    ->get();

            return DataTables::of($rows)
                    ->addColumn('budget', function($row) {
                                return '$' . number_format($row->budget,'2');
                            })
                   ->addColumn('action', function($row) {

                        $routeEdit = route('webpages.show', $row->id);
                        $routeDelete = route('webpages.destroy', $row->id);

                        $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->title.'" id="btnDelete">
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns(['image', 'action'])
                    ->make(true);
        }

        $tournaments = [];
        if (Auth::user()->role === 'Admin') {
            $tournaments = Tournaments::where('is_deleted','0')->where('status','!=','Terminado')->get(['id','title']);
        } else {
            $tournaments = Tournaments::where('is_deleted','0')->where('status','!=','Terminado')->where('user_id',Auth::user()->id)->get(['id','title']);
        }

        $pCategories = [];
        if (Auth::user()->role === 'Admin') {
            $pCategories = PageCategory::where('is_deleted','0')
            ->get(['id','name']);
        }

        return view ('webpages.index', [
            'tournaments' => $tournaments,
            'pCategories' => $pCategories
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
            'title'         => 'required|max:120',
            'description'   => 'required|max:120',
            'content'       => 'required',
            'status'        => 'required',
            'type'          => 'required',
            'location'      => 'required'
        ];

        $attributes = [
            'title'         => 'Título es obligatorio',
            'description'   => 'Descripción es obligatorio',
            'content'       => 'Contenido es obligatorio',
            'status'        => 'Estatus es obligatorio',
            'type'          => 'Tipo es obligatorio',
            'location'      => 'Ubicación es obligatorio'
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el registro!',
                'errors' => $validated->errors()
            ]);
        }

        $imagePath = "";
        if($request->file('image')){
            $imagePath =  date('YmdHis') . '.' .$request->file('image')->extension();
            $request->file('image')->storeAs('public/web_pages', $imagePath);
        }


        if (
            !isset($request->id)
            || empty($request->id)
        ) {
            $model = new WebPage($request->all());
            $model->user_id = Auth::user()->id;
            $model->slug = str_slug($model->title);


            if (!empty($imagePath))
                $model->image = $imagePath;

            if ($model->save())
                 return response()->json(['type' => 'success',
                                 'message' => 'Se ha creado el contenido web!']);
        } else {
            $model = WebPage::find($request->id);
            $lastImage = $model->image;
            $model->fill($request->all());
            $model->slug = str_slug($model->title);


            if (!empty($imagePath)) {
                Storage::delete('public/web_pages/' . $lastImage);
                $model->image = $imagePath;
            }

            if ($model->update())
                return response()->json(['type' => 'success',
                                 'message' => 'Se ha modificado el contenido web!']);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el contenido web!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Webpage  $sebpage
     * @return \Illuminate\Http\Response|bool
     */
    public function show($id)
    {
        if ($id) {
            return  Webpage::select(
                'id',
                'user_id',
                'title',
                'description',
                'budget',
                'content',
                'status',
                'type',
                'location',
                'tournament_id',
                'page_category_id',
            )->find($id);
        }
        return false;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sponsor  $sponsor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id) && $id > 0) {
            WebPage::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'La página  se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar El patronciador!'
        ]);
    }

    public function showWebPage($slug)
    {
        if (!$slug) {
            return view('errors.501');
        }

        $webpage = WebPage::where('slug', $slug)->get();
        return view('webpages.show', [
            'webpage' => $webpage[0]
        ]);
    }
}
