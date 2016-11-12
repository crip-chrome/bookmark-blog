<?php namespace App\Http\ApiControllers;

use App\Bookmark;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class BookmarksController
 * @package App\Http\ApiControllers
 */
class BookmarksController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function created(Request $request)
    {
        $model = new Bookmark($request);
        $model->save();
        dd($model->toArray());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function changed(Request $request)
    {
        dd(new Bookmark($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function moved(Request $request)
    {
        dd(new Bookmark($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function removed(Request $request)
    {
        dd(new Bookmark($request));
    }
}