<?php namespace App\Http\Controllers\Admin;

use App\Bookmark;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBookmarkRequest;
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
        $relations = $this->implodeQuery('parent');
        $relations[] = 'category';
        $relations['children'] = function ($query) {
            $query->orderBy('index')->currentUser()->orderBy('date_added');
        };

        $bookmark = $this->bookmark->newQuery()->currentUser()->page($page_id)
            ->with($relations)->firstOrFail();

        return JsonResponse::create($bookmark);
    }

    /**
     * @param UpdateBookmarkRequest $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveBookmark(UpdateBookmarkRequest $request)
    {
        $bookmark = $this->bookmark->newQuery()->currentUser()->page($request->page_id)
            ->with($this->implodeQuery('children'))->firstOrFail();

        $this->save(
            $bookmark,
            !!$request->visible,
            $request->category_id ? $request->category_id : null
        );

        return JsonResponse::create($bookmark);
    }

    private function implodeQuery($key, $times = 100)
    {
        $times--;
        return [$key => function ($query) use ($key, $times) {
            if ($times > 0)
                $query->currentUser()->with($this->implodeQuery($key, $times));
            else
                $query->currentUser();
        }];
    }

    private function save($bookmark, $value, $category_id = null)
    {
        $bookmark->visible = $value;

        if ($category_id !== null)
            $bookmark->category_id = $category_id;

        $bookmark->save();

        foreach ($bookmark->children as $children) {
            $this->save($children, $value, $category_id);
        }
    }
}