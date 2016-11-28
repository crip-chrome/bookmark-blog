<?php namespace App\Http\Controllers\Admin;

use App\Bookmark;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers\Admin
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(\App::environment() != 'production') {
            \Debugbar::info(sprintf('Current PHP version: %s', phpversion()));
            \Debugbar::info(sprintf('Current User id: %s', \Auth::user()->id));
        }

        $bookmark = Bookmark::where('user_id', \Auth::user()->id)->where('parent_id', 0)->first();

        return view('admin.home')->with('bookmark', $bookmark);
    }
}
