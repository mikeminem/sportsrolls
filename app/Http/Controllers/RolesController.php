<?php

namespace App\Http\Controllers;

use App\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Roles::where('is_deleted', 0))
                ->addColumn('action', function ($row) {
                    return '<a href="'.route('roles.show', $row->id).'" title="Editar rol" id="btnUpdate"><span class="fa fa-edit tool"></span></a> '
                        .'<a href="'.route('roles.destroy', $row->id).'" title="Borrar rol" data-name="'.e($row->name).'" id="btnDelete"><span class="fa fa-trash tool"></span></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('roles.index');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id' => 'nullable|integer|min:1',
            'name' => 'required|string|max:20',
            'description' => 'nullable|string|max:500',
            'is_default' => 'sometimes|boolean',
        ], [], ['name' => 'Nombre', 'description' => 'Descripción', 'is_default' => 'Predeterminado']);

        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el rol.',
                'errors' => $validated->errors(),
            ]);
        }

        $creating = !$request->filled('id');
        $model = $creating ? new Roles : Roles::where('is_deleted', 0)->findOrFail($request->id);
        $model->name = $request->name;
        $model->description = $request->input('description') ?? '';
        $model->is_default = $request->input('is_default', 0);
        $model->save();

        return response()->json([
            'type' => 'success',
            'message' => $creating ? 'Se ha creado el rol.' : 'Se ha modificado el rol.',
        ]);
    }

    public function show($id)
    {
        return Roles::where('is_deleted', 0)->findOrFail($id);
    }

    public function destroy($id)
    {
        $model = Roles::where('is_deleted', 0)->findOrFail($id);
        $model->is_deleted = 1;
        $model->save();

        return response()->json(['type' => 'success', 'message' => 'El rol se ha borrado.']);
    }

    public function list()
    {
        return Roles::where('is_deleted', 0)->get();
    }
}
