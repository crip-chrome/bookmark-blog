<?php namespace App\Http\Controllers;

use App\Bookmark;
use App\Tag;
use App\User;

/**
 * Class BookmarksController
 * @package App\Http\Controllers
 */
class BookmarksController extends Controller
{
    /**
     * @var Bookmark
     */
    private $bookmark;

    /**
     * @var Tag
     */
    private $tag;

    /**
     * @var User
     */
    private $user;

    /**
     * BookmarksController constructor.
     * @param Bookmark $bookmark
     * @param Tag $tag
     * @param User $user
     */
    public function __construct(Bookmark $bookmark, Tag $tag, User $user)
    {
        $this->bookmark = $bookmark;
        $this->tag = $tag;
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $tag_id = null;
        $related_to = null;
        $paging = $this->bookmark->newQuery()->orderBy('date_added', false)->with(['user', 'tags'])
            ->where('url', '<>', '')->where('visible', true)->paginate();

        $days = $this->toGroupedList($paging);
        $tags = $this->getMostUsedTags();

        return view('bookmarks.home')->with(compact('days', 'paging', 'tags', 'tag_id', 'related_to'));
    }

    /**
     * Display bookmarks related to author
     *
     * @param int $author_id
     * @return $this
     */
    public function author(int $author_id)
    {
        $tag_id = null;
        $author = $this->user->newQuery()->where('id', $author_id)->firstOrFail();
        $related_to = $author->name;

        $paging = $this->bookmark->newQuery()->orderBy('date_added', false)->with(['user', 'tags'])
            ->where('url', '<>', '')->where('visible', true)->where('user_id', $author_id)->paginate();

        $days = $this->toGroupedList($paging);
        $tags = $this->getMostUsedTags();

        return view('bookmarks.home')->with(compact('days', 'paging', 'tags', 'tag_id', 'related_to'));
    }

    /**
     * Display bookmarks related to tag
     *
     * @param int $tag_id
     * @return $this
     */
    public function tag(int $tag_id)
    {
        $tag = $this->tag->newQuery()->where('id', $tag_id)->firstOrFail();
        $related_to = $tag->tag;
        $paging = $this->bookmark->newQuery()->orderBy('date_added', false)->with(['user', 'tags'])
            ->where('url', '<>', '')->where('visible', true)->whereIn('id', function ($q) use ($tag_id) {
                $bookmark_tags = Tag::$bookmarks;
                $tags = $this->tag->getTable();
                $q->from("${$bookmark_tags} as pivot")
                    ->join("${$tags} as t", 't.id', '=', 'pivot.tag_id')
                    ->where('t.id', $tag_id)
                    ->select('pivot.bookmark_id');
            })->paginate();

        $days = $this->toGroupedList($paging);
        $tags = $this->getMostUsedTags();

        return view('bookmarks.home')->with(compact('days', 'paging', 'tags', 'tag_id', 'related_to'));
    }

    /**
     * @param $bookmarks
     * @param string $group_by
     * @return array
     */
    private function toGroupedList($bookmarks, $group_by = 'date_added')
    {
        $groups = [];

        foreach ($bookmarks as $bookmark) {
            $group = $bookmark->$group_by->toDateString();
            if (!array_key_exists($group, $groups))
                $groups[$group] = [];

            $groups[$group][] = $bookmark;
        }

        return $groups;
    }

    /**
     * @param int $count
     * @param int $minimum_tags
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getMostUsedTags(int $count = 25, int $minimum_tags = 5)
    {
        $bookmark_tags = Tag::$bookmarks;
        return $this->tag->newQuery()->join(
            \DB::raw("(SELECT tag_id, COUNT(tag_id) as tag_count FROM ${$bookmark_tags} GROUP BY tag_id) AS tc"),
            'tags.id', '=', 'tc.tag_id'
        )->where('tag_count', '>', $minimum_tags)->orderBy('tag_count', 'desc')->limit($count)->get();
    }
}