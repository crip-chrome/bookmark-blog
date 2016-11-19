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
        $this->bookmark = $bookmark->newQuery();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('bookmarks.home');
    }
}