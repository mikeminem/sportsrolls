<?php

namespace App\Http\Controllers;

use App\Stadiums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StadiumsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('stadiums.index');
    }

    /**
     * Store or update a stadium in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $rules = [
            'name'      => 'required|max:120',
            'address'   => 'required|max:120',
            'is_active' => 'required',
        ];

        $attributes = [
            'name'      => 'Nombre',
            'manager'   => 'Encargado',
            'phone'     => 'Teléfono / Móvil',
            'address'   => 'Dirección',
            'is_active' => 'Activo',
        ];

        //Validate the data
        $validated = Validator::make($request->all(), $rules, [], $attributes);

        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el Estadio!',
                'errors' => $validated->errors()
            ]);
        }

        if (!isset($request->id) || empty($request->id)) {
            //create a new row
            $model = new Stadiums($request->only(['name', 'address', 'manager', 'phone', 'notes', 'is_active']));
            $model->code = strtoupper(str_random(6));
            $model->user_id = Auth::user()->id;
            if ($model->save()) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Se ha creado el estadio!'
                ]);
            }
        }

        if (isset($request->id) && !empty($request->id)) {
            $model = Stadiums::find($request->id);

            // Verificar autorización
            if (!$model || ((string) Auth::id() !== (string) $model->user_id && !Auth::user()->hasRole('admin'))) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'No tienes permiso para actualizar este estadio!'
                ]);
            }

            $model->fill($request->only(['name', 'address', 'manager', 'phone', 'notes', 'is_active']));

            if ($model->update()) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Se ha modificado el estadio!'
                ]);
            }
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el Estadio!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Stadiums|null
     */
    public function show($id)
    {
        if ($id) {
            return Stadiums::where('id', $id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        if (!$request->idDelete) {
            return response()->json([
                'type' => 'error',
                'message' => 'ID del estadio no proporcionado!'
            ]);
        }

        $stadium = Stadiums::find($request->idDelete);

        // Verificar autorización
        if (!$stadium || ((string) Auth::id() !== (string) $stadium->user_id && !Auth::user()->hasRole('admin'))) {
            return response()->json([
                'type' => 'error',
                'message' => 'No tienes permiso para eliminar este estadio!'
            ]);
        }

        $stadium->update(['is_deleted' => 1]);

        return response()->json([
            'type' => 'success',
            'message' => 'El estadio se ha borrado!'
        ]);
    }

    /**
     * Get all stadiums for DataTables display.
     *
     * @return mixed
     */
    public function allStadiums()
    {
        if (Auth::user()->hasRole('admin')) {
            $rows = DB::table('stadiums as s')
                ->select('s.id', 's.code', 's.name', 's.address', 's.manager', 's.phone', 's.notes', 's.is_active')
                ->where('s.is_deleted', 0)
                ->get();
        } else {
            $rows = DB::table('stadiums as s')
                ->select('s.id', 's.code', 's.name', 's.address', 's.manager', 's.phone', 's.notes', 's.is_active')
                ->where('s.is_deleted', 0)
                ->where('s.user_id', Auth::user()->id)
                ->get();
        }

        return DataTables::of($rows)
            ->addColumn('is_active', function ($row) {
                $combo = '<select id="cboIsActive" class="form-control" data-id="' . e($row->id) . '" >';

                if ($row->is_active == 1) {
                    $combo .= '<option value="1" selected>Si</option>';
                } else {
                    $combo .= '<option value="1">Si</option>';
                }

                if ($row->is_active == 0) {
                    $combo .= '<option value="0" selected>No</option>';
                } else {
                    $combo .= '<option value="0">No</option>';
                }

                $combo .= '</select>';
                return $combo;
            })
            ->addColumn('action', function ($rows) {
                $links = '';
                if ((Auth::user()->hasRole('admin') || Auth::user()->hasRole('promoter'))) {
                    $links .= '<a href="javascript:void(0);" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate" data-id="' . e($rows->id) . '" ><span class="fa fa-edit tool"></span></a>
                             <a href="javascript:void(0);" title="Borrar Registro" data-id="' . e($rows->id) . '" data-name="' . e($rows->name) . '" id="btnDelete"> <span class="fa fa-trash tool"></span></a>';
                }

                return $links;
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    /**
     * Print stadium information.
     *
     * @param  int  $id
     * @return void
     */
    public function Print($id)
    {
        echo "Print";
    }

    /**
     * Toggle stadium active status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function isActiveToggle(Request $request)
    {
        if (!$request->id) {
            return response()->json([
                'type' => 'error',
                'message' => 'ID del estadio no proporcionado!'
            ]);
        }

        $stadium = Stadiums::find($request->id);

        // Verificar autorización
        if (!$stadium || ((string) Auth::id() !== (string) $stadium->user_id && !Auth::user()->hasRole('admin'))) {
            return response()->json([
                'type' => 'error',
                'message' => 'No tienes permiso para actualizar este estadio!'
            ]);
        }

        $stadium->is_active = $request->is_active;
        if ($stadium->update()) {
            return response()->json([
                'type' => 'success',
                'message' => 'El estado del estadio se ha actualizado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar actualizar el estado del Estadio!'
        ]);
    }
}
