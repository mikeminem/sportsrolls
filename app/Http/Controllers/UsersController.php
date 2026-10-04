<?php

namespace App\Http\Controllers;

use App\User;
use App\Roles;
use Illuminate\Validation\Rule;

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
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()->hasRole('admin'), 403);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
         if($request->ajax()){
            $rows = User::with('roles')->where('is_deleted', 0)->get();

            return DataTables::of($rows)
                    ->editColumn('role', function($row) {
                        return $row->roles->pluck('name')->implode(', ') ?: $row->role;
                    })
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

        return view('users.index', ['roles' => Roles::where('is_deleted', 0)->orderBy('name')->get()]);
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
            'last_name'   => 'required|max:120',
            'id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('is_deleted', 0)],
            'roles' => 'required|array|min:1',
            'roles.*' => ['required', 'integer', 'distinct', Rule::exists('roles', 'id')->where('is_deleted', 0)]
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

        $creating = !$request->filled('id');
        DB::transaction(function () use ($request, $creating) {
            $model = $creating ? new User : User::findOrFail($request->id);
            $model->fill($request->only(['name', 'last_name', 'email', 'phone', 'active']));
            if ($request->filled('password')) {
                $model->password = Hash::make($request->password);
            } elseif ($creating) {
                $model->password = '';
            }

            // Keep existing single-role authorization compatible with the selected roles.
            $names = Roles::whereIn('id', $request->roles)->pluck('name')->map(function ($name) {
                return mb_strtolower($name, 'UTF-8');
            })->all();
            $model->role = 'Player';
            foreach (['Admin', 'Promoter', 'Vendor', 'Player'] as $legacyRole) {
                if (in_array(mb_strtolower($legacyRole, 'UTF-8'), $names, true)) {
                    $model->role = $legacyRole;
                    break;
                }
            }
            $model->save();
            $model->roles()->sync($request->roles);
        });

        return response()->json([
            'type' => 'success',
            'message' => $creating ? 'Se ha creado el Usuario!' : 'Se ha modificado el Usuario!'
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
            $User = User::select('id', 'name', 'last_name', 'email', 'phone', 'role', 'active')->findOrFail($id);
            $roleIds = $User->roles()->pluck('roles.id');
            // Preselect the old role for users that have not yet been migrated.
            if (!DB::table('user_roles')->where('user_id', $User->id)->exists()) {
                $roleIds = Roles::where('is_deleted', 0)->where('name', $User->role)->pluck('id');
            }
            $User->setAttribute('role_ids', $roleIds);
            
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
