<?php namespace App\Http\Controllers\Admin;

use App\Bookmark;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param int $page_id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getBookmark($page_id)
    {
        $bookmark = $this->bookmark->newQuery()->currentUser()->page($page_id)
            ->with(['children' => function ($query) {
                $query->orderBy('index')->orderBy('date_added');
            }, implode('.', array_fill(0, 100, 'parent'))])->firstOrFail();

        return JsonResponse::create($bookmark);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveBookmark(Request $request)
    {
        $bookmark = $this->bookmark->newQuery()->currentUser()->page($request->page_id)->firstOrFail();

        $bookmark->visible = !!$request->visible;
        $bookmark->save();

        return JsonResponse::create($bookmark);
    }
}