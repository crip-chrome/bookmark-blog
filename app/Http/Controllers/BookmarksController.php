<?php namespace App\Http\Controllers;

use App\Bookmark;

/**
 * Class BookmarksController
 * @package App\Http\Controllers
 */
class BookmarksController extends Controller
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $bookmark;

    /**
     * BookmarksController constructor.
     * @param Bookmark $bookmark
     */
    public function __construct(Bookmark $bookmark)
    {
        $this->bookmark = $bookmark;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $bookmarks = $this->bookmark->newQuery()->orderBy('date_added', false)->with('user')
            ->where('url', '<>', '')->where('visible', true)->paginate();

        $result = [];

        foreach ($bookmarks as $bookmark) {
            $date = $bookmark->date_added->toDateString();
            if (!array_key_exists($date, $result))
                $result[$date] = [];

            $result[$date][] = $bookmark;
        }

        return view('bookmarks.home')->with(['days' => $result, 'paging' => $bookmarks]);
    }
}