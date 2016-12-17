<?php namespace App\Http\ApiControllers;

use App\Bookmark;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiChangedBookmarkRequest;
use App\Http\Requests\ApiCreatedBookmarkRequest;
use App\Http\Requests\ApiMovedBookmarkRequest;
use App\Http\Requests\ApiRemovedBookmarkRequest;
use App\Jobs\SynchronizeBookmarks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class BookmarksController
 * @package App\Http\ApiControllers
 */
class BookmarksController extends Controller
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $bookmark;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->bookmark = (new Bookmark())->newQuery();
    }

    /**
     * @param ApiCreatedBookmarkRequest $request
     * @return Bookmark
     */
    public function created(ApiCreatedBookmarkRequest $request)
    {
        $attributes = [
            'parent_id' => $request->parentId,
            'page_id' => $request->id,
            'date_added' => $request->dateAdded,
            'title' => $request->title,
            'url' => $request->url,
            'index' => $request->index,
            'old_index' => $request->oldIndex,
            'old_parent_id' => $request->oldParentId,
            'user_id' => \Auth::user()->id,
        ];

        $parent = $this->find($request->parentId);
        if ($parent) {
            $attributes['visible'] = $parent->visible;
        }

        $model = new Bookmark($attributes);
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
     * @param Request $request
     * @return JsonResponse
     */
    public function sync(Request $request)
    {
        $data = $request->toArray();
        $job = (new SynchronizeBookmarks($data, \Auth::user()->id))->onQueue('bookmarks');
        $this->dispatch($job);

        return new JsonResponse(['message' => 'Request will be processed in background in minutes.']);
    }

    /**
     * Recursive tree delete
     *
     * @param Bookmark $model
     * @private
     */
    private function deleteChildren(Bookmark $model)
    {
        $children = $this->bookmark->where('user_id', \Auth::user()->id)
            ->where('parent_id', $model->id)
            ->get();

        foreach ($children as $child) {
            /** @var Bookmark $child */
            $this->deleteChildren($child);
        }

        $model->delete();
    }

    /**
     * Find Bookmark by auth user id and page_id
     *
     * @param int $id
     * @return Bookmark
     * @private
     */
    private function find(int $id)
    {
        /** @var Bookmark $model */
        $model = $this->bookmark->where('user_id', \Auth::user()->id)
            ->where('page_id', $id)
            ->first();

        return $model;
    }
}