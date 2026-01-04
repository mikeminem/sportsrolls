<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('products as p')
                ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.product_category_id')
                ->select('p.id', 'p.sku', 'p.name', 'p.slug', 'p.description', 'p.image', 'p.price', 'p.stock', 'p.is_active', 'pc.name as category')
                ->where('p.is_deleted', 0);

            if ($request->filled('category')) {
                $query->where('p.product_category_id', $request->input('category'));
            }

            $rows = $query->get();

            return DataTables::of($rows)
                ->addColumn('is_active', function ($row) {
                    return ($row->is_active ? 'Si' : 'No');
                })
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        $url = route('products.image', ['filename' => $row->image]);
                        return '<img src="' . e($url) . '" alt="' . e($row->name) . '" style="height:32px;width:auto;" />';
                    }
                    return '';
                })
                ->addColumn('action', function ($row) {
                    $routeEdit = route('products.show', $row->id);
                    $routeDelete = route('products.destroy', $row->id);

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

        $categories = ProductCategory::where('is_deleted', 0)->where('is_active', 1)->get(['id','name']);
        return view('products.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'product_category_id' => 'required|exists:product_categories,id',
            'sku' => 'nullable|string|max:60',
            'name' => 'required|max:160',
            'slug' => 'nullable|max:160',
            'description' => 'nullable|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];

        $attributes = [
            'product_category_id' => 'Categoría',
            'sku' => 'SKU',
            'name' => 'Nombre',
            'slug' => 'Slug',
            'description' => 'Descripción',
            'image' => 'Imagen',
            'price' => 'Precio',
            'stock' => 'Existencia',
            'is_active' => 'Activo',
        ];

        $validated = Validator::make($request->all(), $rules, [], $attributes);
        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el producto!',
                'errors' => $validated->errors(),
            ]);
        }

        $isUpdate = isset($request->id) && !empty($request->id);

        if (!$isUpdate) {
            $model = new Product();
        } else {
            $model = Product::find($request->id);
            if (!$model) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Producto no encontrado para actualizar',
                ]);
            }
        }

        $model->product_category_id = (int) $request->input('product_category_id');
        $model->sku = $request->input('sku');
        $model->name = $request->input('name');
        $model->slug = $request->input('slug');
        $model->description = $request->input('description');
        $model->price = (float) $request->input('price');
        $model->stock = (int) $request->input('stock', 0);
        $model->is_active = $request->input('is_active', 0);
        $model->is_deleted = 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $filename = basename($path);
            $model->image = $filename;
        }

        if (!$isUpdate) {
            if ($model->save()) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Se ha creado el producto!',
                ]);
            }
        } else {
            if ($model->update()) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Se ha modificado el producto!',
                ]);
            }
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el producto!',
        ]);
    }

    public function show($id)
    {
        if ($id) {
            $product = Product::select('id', 'product_category_id', 'sku', 'name', 'slug', 'description', 'image', 'price', 'stock', 'is_active')
                ->find($id);
            return $product ?: false;
        }
        return false;
    }

    public function destroy($id)
    {
        if (isset($id) && $id > 0) {
            Product::where('id', $id)->update(['is_deleted' => 1]);

            return response()->json([
                'type' => 'success',
                'message' => 'El producto se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el producto!'
        ]);
    }

    public function list()
    {
        $list = Product::where('is_deleted', 0)->where('is_active', 1)->get();
        return (string) $list;
    }

    public function image($filename)
    {
        $path = storage_path('app/public/products/' . $filename);

        if (!File::exists($path)) abort(404);

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = response($file, 200);
        $response->header('Content-Type', $type);

        return $response;
    }
}
