<?php

namespace App\Http\Controllers;

use App\PageCategory;
use App\WebPage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

class PageCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['listPage']]);
    }

    public function index(Request $request)
    {
         if($request->ajax()){

            $rows = [];
            $rows = DB::table('page_category as c')
                ->select('c.id', 'c.name', 'c.description', 'c.status', 'c.views', 'c.prints', 'c.likes')
                ->where('c.is_deleted', 0)
                ->where('c.user_id',Auth::user()->id)
                ->get();

            return DataTables::of($rows)
                    ->addColumn('action', function($row) {

                        $routeEdit = route('pagecategories.show', $row->id);
                        $routeDelete = route('pagecategories.destroy', $row->id);

                        $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->name.'" id="btnDelete">
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view ('page_categories.index', [
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
            'name'          => 'required|max:120',
            'description'   => 'required|max:120',
            'keywords'      => 'required|max:120',
            'status'        => 'required',
        ];

        $attributes = [
            'name'          => 'Nombre',
            'description'   => 'Ddescripción',
            'status'        => 'Mostrar',
            'keywords'      => 'Palabras Clave',
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar la categoria!',
                'errors' => $validated->errors()
            ]);
        }


        if (
            !isset($request->id)
            || empty($request->id)
        ) {
            $model = new PageCategory($request->all());
            $model->user_id = Auth::user()->id;
            $model->slug = str_slug($model->name);

            if ($model->save())
                 return response()->json(['type' => 'success',
                                 'message' => 'Se ha creado la categoria!']);
        } else {
            $model = PageCategory::find($request->id);
            $model->fill($request->all());
            $model->slug = str_slug($model->name);

            if ($model->update())
                return response()->json(['type' => 'success',
                                 'message' => 'Se ha modificado la categoria!']);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la categoria!'
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
        if ($id) {
            $pageCategory = PageCategory::select('id', 'user_id', 'name', 'description', 'slug', 'image', 'keywords', 'icon')->find($id);
            return $pageCategory;
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
        if (isset($id) && $id > 0) {
            PageCategory::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'La categoria se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar la categoria!'
        ]);
    }

    public function list($tournament_id) {

        if ($tournament_id) {
            $list = PageCategory::where('tournament_id',$tournament_id)->where('is_deleted',0)->get();
            return (string) $list;
        }

        return (string) false;
    }

    public function listPage($slug)
    {
        if (!$slug) {
            abort(404);
        }

        $category = PageCategory::where('slug', $slug)
            ->where('is_deleted', 0)
            ->first();

        // $webPages = DB::table('webpages as wp')
        //     //->select('wp.id', 'wp.title', 'wp.slug', 'wp.content', 'wp.image', 'wp.keywords', 'wp.views', 'wp.likes', 'wp.prints', 'wp.created_at')
        //     ->select('wp.id', 'wp.title', 'wp.slug', 'wp.description', 'wp.image', 'wp.keywords', 'wp.views', 'wp.likes', 'wp.prints', 'wp.created_at')
        //     ->where('wp.page_category_id', $category->id)
        //     ->where('wp.is_deleted', 0)
        //     ->orderBy('wp.created_at', 'DESC')
        //     ->get();

        $pages = WebPage::where('page_category_id', $category->id)
            ->where('is_deleted', 0)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view ('page_categories.list_page', [
            'category' => $category,
            'pages' => $pages,
        ]);
    }
}
