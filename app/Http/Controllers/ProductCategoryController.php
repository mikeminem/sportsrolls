<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = DB::table('product_categories as pc')
                ->select('pc.id', 'pc.name', 'pc.slug', 'pc.icon', 'pc.image', 'pc.is_active')
                ->where('pc.is_deleted', 0)
                ->get();

            return DataTables::of($rows)
                ->addColumn('is_active', function ($row) {
                    return ($row->is_active ? 'Si' : 'No');
                })
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        $url = route('productcategories.image', ['filename' => $row->image]);
                        return '<img src="' . e($url) . '" alt="' . e($row->name) . '" style="height:32px;width:auto;" />';
                    }
                    return '';
                })
                ->addColumn('action', function ($row) {
                    $routeEdit = route('productcategories.show', $row->id);
                    $routeDelete = route('productcategories.destroy', $row->id);

                    $links = '<a href="' . e($routeEdit) . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">'
                        . '<span class="fa fa-edit tool"></span>'
                        . '</a>'
                        . '<a href="' . e($routeDelete) . '" title="Borrar Registro" data-name="' . e($row->name) . '" id="btnDelete">'
                        . '<span class="fa fa-trash tool"></span>'
                        . '</a>';

                    return $links;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('product_categories.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:120',
            'slug' => 'nullable|max:160',
            'icon' => 'nullable|max:160',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ];

        $attributes = [
            'name' => 'Nombre',
            'slug' => 'Slug',
            'icon' => 'Icono',
            'image' => 'Imagen',
            'is_active' => 'Activo',
        ];

        $validated = Validator::make($request->all(), $rules, [], $attributes);
        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar la categoría de productos!',
                'errors' => $validated->errors(),
            ]);
        }

        $isUpdate = isset($request->id) && !empty($request->id);

        if (!$isUpdate) {
            $model = new ProductCategory();
        } else {
            $model = ProductCategory::find($request->id);
            if (!$model) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Categoría no encontrada para actualizar',
                ]);
            }
        }

        $model->name = $request->input('name');
        $model->slug = $request->input('slug');
        $model->icon = $request->input('icon');
        $model->is_active = $request->input('is_active', 0);
        $model->is_deleted = 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product_categories', 'public');
            $filename = basename($path);
            $model->image = $filename;
        }

        if (!$isUpdate) {
            if ($model->save()) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Se ha creado la categoría de productos!',
                ]);
            }
        } else {
            if ($model->update()) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Se ha modificado la categoría de productos!',
                ]);
            }
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la categoría de productos!',
        ]);
    }

    public function show($id)
    {
        if ($id) {
            $category = ProductCategory::select('id', 'name', 'slug', 'icon', 'image', 'is_active')
                ->find($id);
            return $category ?: false;
        }
        return false;
    }

    public function destroy($id)
    {
        if (isset($id) && $id > 0) {
            ProductCategory::where('id', $id)->update(['is_deleted' => 1]);

            return response()->json([
                'type' => 'success',
                'message' => 'La categoría de productos se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar la categoría de productos!'
        ]);
    }

    public function list()
    {
        $list = ProductCategory::where('is_deleted', 0)->where('is_active', 1)->get();
        return (string) $list;
    }

    public function image($filename)
    {
        $path = storage_path('app/public/product_categories/' . $filename);

        if (!\File::exists($path)) abort(404);

        $file = \File::get($path);
        $type = \File::mimeType($path);

        $response = response($file, 200);
        $response->header('Content-Type', $type);

        return $response;
    }
}
