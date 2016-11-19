<?php namespace App\Http\Controllers\Admin;

use App\Bookmark;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Class BookmarksApiController
 * @package App\Http\Controllers\Admin
 */
class BookmarksApiController extends Controller
{
    /**
     * @var Bookmark
     */
    private $bookmark;

    /**
     * BookmarksApiController constructor.
     * @param Bookmark $bookmark
     */
    public function __construct(Bookmark $bookmark)
    {
        $this->middleware('auth');
        $this->bookmark = $bookmark;
    }

    /**
     * @param {int} $page_id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getByParent($page_id)
    {
        $bookmark = $this->bookmark->newQuery()
            ->where('user_id', \Auth::user()->id)
            ->where('page_id', $page_id)
            ->firstOrFail(['title'])
            ->toArray();

        $bookmark['children'] = $this->bookmark->newQuery()
            ->where('user_id', \Auth::user()->id)
            ->where('parent_id', $page_id)
            ->get();

        return JsonResponse::create($bookmark);
    }
}