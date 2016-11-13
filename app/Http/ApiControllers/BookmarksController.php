<?php namespace App\Http\ApiControllers;

use App\Bookmark;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiChangedBookmarkRequest;
use App\Http\Requests\ApiCreatedBookmarkRequest;
use App\Http\Requests\ApiMovedBookmarkRequest;
use App\Http\Requests\ApiRemovedBookmarkRequest;
use Illuminate\Http\JsonResponse;
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
     * @param ApiChangedBookmarkRequest $request
     * @return Bookmark
     */
    public function changed(ApiChangedBookmarkRequest $request)
    {
        $model = $this->find($request->id);

        $model->title = $request->title;
        $model->url = $request->url;

        $model->save();

        return $model;
    }

    /**
     * @param ApiMovedBookmarkRequest $request
     * @return Bookmark
     */
    public function moved(ApiMovedBookmarkRequest $request)
    {
        $model = $this->find($request->id);

        $model->index = $request->index;
        $model->parent_id = $request->parentId;

        $model->save();

        return $model;
    }

    /**
     * @param ApiRemovedBookmarkRequest $request
     * @return JsonResponse
     */
    public function removed(ApiRemovedBookmarkRequest $request)
    {
        $model = $this->find($request->id);
        $this->deleteChildren($model);

        return new JsonResponse('true');
    }

    /**
     * Recursive tree delete
     *
     * @param Bookmark $model
     */
    private function deleteChildren(Bookmark $model)
    {
        $children = Bookmark::where('user_id', \Auth::user()->id)
            ->where('parent_id', $model->id)
            ->get();

        foreach ($children as $child) {
            $this->deleteChildren($child);
        }

        $model->delete();
    }

    /**
     * Find Bookmark by auth user id and page_id
     *
     * @param int $id
     * @return Bookmark
     */
    private function find(int $id)
    {
        $model = Bookmark::where('user_id', \Auth::user()->id)
            ->where('page_id', $id)
            ->first();

        return $model;
    }
}