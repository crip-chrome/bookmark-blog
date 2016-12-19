<?php namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
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
        $categories = $this->category->newQuery()->get();

        return new JsonResponse($categories);
    }
}