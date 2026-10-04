<?php

namespace App\Http\Controllers;

use App\User;
use App\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DataTables;

class UserRolesController extends Controller
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
        $validated = Validator::make($request->all(), ['user_id' => 'nullable|integer|min:1']);
        if ($validated->fails()) {
            return $this->validationError($validated);
        }

        $rows = UserRoles::with('role')->whereHas('user', function ($query) {
            $query->where('is_deleted', 0);
        })->whereHas('role', function ($query) {
            $query->where('is_deleted', 0);
        });
        if ($request->filled('user_id')) {
            $rows->where('user_id', $request->user_id);
        }

        if ($request->ajax()) {
            return DataTables::of($rows)->make(true);
        }

        return response()->json($rows->get());
    }

    // Like CategoryController, POST creates or updates according to the supplied id.
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id' => 'nullable|integer|min:1',
            'user_id' => ['required', 'integer', 'min:1', Rule::exists('users', 'id')->where('is_deleted', 0)],
            'rol_id' => ['required', 'integer', 'min:1', Rule::exists('roles', 'id')->where('is_deleted', 0)],
        ], [], ['user_id' => 'Usuario', 'rol_id' => 'Rol']);

        if ($validated->fails()) {
            return $this->validationError($validated);
        }

        $creating = !$request->filled('id');
        $model = $creating ? new UserRoles : UserRoles::findOrFail($request->id);
        $unique = Rule::unique('user_roles', 'rol_id')->where('user_id', $request->user_id);
        if (!$creating) {
            $unique->ignore($model->id);
        }
        $validated = Validator::make($request->all(), ['rol_id' => [$unique]], [
            'rol_id.unique' => 'El usuario ya tiene asignado este rol.',
        ]);
        if ($validated->fails()) {
            return $this->validationError($validated);
        }

        $model->fill($request->only(['user_id', 'rol_id']));
        $model->save();

        return response()->json([
            'type' => 'success',
            'message' => $creating ? 'Se ha asignado el rol al usuario.' : 'Se ha modificado el rol del usuario.',
            'data' => $model,
        ]);
    }

    public function show($id)
    {
        return UserRoles::with('role')->findOrFail($id);
    }

    public function destroy($id)
    {
        UserRoles::findOrFail($id)->delete();

        return response()->json(['type' => 'success', 'message' => 'Se ha quitado el rol del usuario.']);
    }

    public function list($user_id)
    {
        User::where('is_deleted', 0)->findOrFail($user_id);

        return UserRoles::with('role')->where('user_id', $user_id)
            ->whereHas('role', function ($query) {
                $query->where('is_deleted', 0);
            })->get();
    }

    private function validationError($validator)
    {
        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar o consultar los roles del usuario.',
            'errors' => $validator->errors(),
        ]);
    }
}
