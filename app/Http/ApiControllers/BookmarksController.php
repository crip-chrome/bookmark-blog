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
     * @param Request $request
     */
    public function sync(Request $request)
    {
        $ids = [];

        $this->readChildren($ids, [$request->toArray()]);

        Bookmark::whereNotIn('page_id', $ids)->where('user_id', \Auth::user()->id)->delete();
    }

    /**
     * @param {&array} $ids
     * @param {array} $source
     */
    private function readChildren(&$ids, $source)
    {
        foreach ($source as $children) {
            $ids[] = $children['id'];

            Bookmark::updateOrCreate([
                'page_id' => $children['id'],
                'user_id' => \Auth::user()->id,
            ], [
                'parent_id' => $children['parentId'],
                'date_added' => $children['dateAdded'],
                'title' => $children['title'],
                'index' => $children['index'],
                'url' => array_key_exists('url', $children) ? $children['url'] : '',
            ]);

            if (isset($children['children']) && count($children['children']) > 0) {
                $this->readChildren($ids, $children['children']);
            }
        }
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