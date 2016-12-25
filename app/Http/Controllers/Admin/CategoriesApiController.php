<?php namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use DB;
use Illuminate\Http\JsonResponse;

/**
 * Class CategoriesApiController
 * @package App\Http\Controllers\Admin
 */
class CategoriesApiController extends Controller
{
    /**
     * @var Category
     */
    private $category;

    /**
     * CategoriesApiController constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->middleware('auth');
        $this->category = $category;
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $categories = $this->category->newQuery()->with(['author' => function($q) {
            $q->select(['id', 'name']);
        }])->leftJoin('bookmarks', 'bookmarks.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.title', 'categories.created_by')
            ->get(['categories.id', 'categories.title', 'categories.created_by', DB::raw('count(bookmarks.id) as usages')]);

        return new JsonResponse($categories);
    }

    /**
     * Store category in database
     *
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $model = $request->only(['title']);
        $model['created_by'] = \Auth::user()->id;

        $category = (new Category($model))->save();

        return new JsonResponse($category);
    }
}