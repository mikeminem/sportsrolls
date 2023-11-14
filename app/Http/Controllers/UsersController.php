<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

class UsersController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index(Request $request)
    {
         if($request->ajax()){
            $rows = DB::table('users as u')
                    ->select('u.id', 'u.name', 'u.last_name', 'u.email', 'u.password', 'u.phone', 'u.role', 'u.active as active')
                    ->where('u.is_deleted', 0)
                    ->get();        
            
            return DataTables::of($rows)
                    ->addColumn('active', function($row) {
                        return $row->active == 1 ? 'Si' : 'No';
                    })
                    ->addColumn('action', function($row) {

                        $routeEdit = route('users.show', $row->id);
                        $routeDelete = route('users.destroy', $row->id);

                        $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->name.'" id="btnDelete"> 
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns(['active', 'action'])
                    ->make(true);
        }

        return view ('users.index');
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
            'name'   => 'required|max:120',
            'last_name'   => 'required|max:120'
        ];

        $attributes = [
            'name'   => 'Nombre',
            'last_name'   => 'Apelliidos'
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el Usuario!',
                'errors' => $validated->errors()
            ]);
        }

        $password = '';

        if (!empty($request->password))
            $password = Hash::make($request->password);


        if (!isset($request->id) || empty($request->id)) {
            $model = new User($request->all());
            
            $model->password = $password;

            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el Usuario!']);      
        } else {
            $model = User::find($request->id);
            $model->fill($request->all());
            $model->password = $password;

            
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el Usuario!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el Usuario!'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $User
     * @return boolean
     */
    public function show( $id)
    {
        //
        if ($id) {
            $User = User::select('id', 'name', 'last_name', 'email', 'password', 'phone', 'role', 'active')->find($id);
            
            return $User;
        }

        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (isset($id) && $id > 0) {
            User::where('id', $id)->update(['is_deleted' => 1]);

            return response()->json([
                'type' => 'success',
                'message' => 'El deporte se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el deporte!'
        ]);
    }
}
