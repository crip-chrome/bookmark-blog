<?php namespace App\Http\ApiControllers;

use App\Bookmark;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiCreatedBookmarkRequest;
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
     * @param ApiCreatedBookmarkRequest $request
     * @return Bookmark
     */
    public function created(ApiCreatedBookmarkRequest $request)
    {
        $model = new Bookmark($request);
        $model->save();

        return $model;
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