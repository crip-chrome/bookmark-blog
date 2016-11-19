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
    public function getBookmarkChildren($page_id)
    {
        $children = $this->bookmark
            ->newQuery()
            ->currentUser()
            ->parent($page_id)
            ->get();

        return JsonResponse::create($children);
    }

    /**
     * @param {int} $page_id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getBookmarkTree($page_id)
    {
        $current = $this->bookmark
            ->newQuery()
            ->currentUser()
            ->page($page_id)
            ->firstOrFail()
            ->toArray();

        $tree = [];
        $this->addParent($tree, $current['parent_id']);

        return JsonResponse::create(compact('current', 'tree'));
    }

    private function addParent(&$target, $parent_id)
    {
        if ($parent_id != 0) {
            $next = $this->bookmark
                ->newQuery()
                ->currentUser()
                ->page($target['parent_id'])
                ->firstOrFail()
                ->toArray();

            array_unshift($target, $next);

            $this->addParent($target, $next['parent_id']);
        }
    }
}