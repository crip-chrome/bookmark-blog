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
            }, $this->implodeStrings('parent')])->firstOrFail();

        return JsonResponse::create($bookmark);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveBookmark(Request $request)
    {
        $bookmark = $this->bookmark->newQuery()->currentUser()->page($request->page_id)
            ->with($this->implodeStrings('children'))->firstOrFail();

        $this->saveVisibility($bookmark, !!$request->visible);

        return JsonResponse::create($bookmark);
    }

    private function implodeStrings($val, $times = 100)
    {
        return implode('.', array_fill(0, $times, $val));
    }

    private function saveVisibility($bookmark, $value)
    {
        $bookmark->visible = $value;
        $bookmark->save();

        foreach ($bookmark->children as $children) {
            $this->saveVisibility($children, $value);
        }
    }
}