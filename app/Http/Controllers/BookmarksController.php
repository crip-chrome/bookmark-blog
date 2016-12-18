<?php namespace App\Http\Controllers;

use App\Bookmark;
use App\Tag;
use App\User;
use DB;
use View;

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

        View::share([
            'tag_id' => 0,
            'related_to' => null,
            'author_id' => 0,
            'tags' => $this->getMostUsedTags(),
            'authors' => $this->getMostPopularAuthors()
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $paging = $this->bookmark->newQuery()->orderBy('date_added', false)->with(['user', 'tags'])
            ->where('url', '<>', '')->where('visible', true)->paginate();
        $days = $this->toGroupedList($paging);

        return view('bookmarks.home')->with(compact('days', 'paging'));
    }

    /**
     * Display bookmarks related to author
     *
     * @param int $author_id
     * @return $this
     */
    public function author(int $author_id)
    {
        $author = $this->user->newQuery()->where('id', $author_id)->firstOrFail();
        $related_to = $author->name;

        $paging = $this->bookmark->newQuery()->orderBy('date_added', false)->with(['user', 'tags'])
            ->where('url', '<>', '')->where('visible', true)->where('user_id', $author_id)->paginate();

        $days = $this->toGroupedList($paging);

        return view('bookmarks.home')->with(compact('days', 'paging', 'related_to', 'author_id'));
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
                $q->from("bookmark_tags AS pivot")
                    ->join("tags AS t", 't.id', '=', 'pivot.tag_id')
                    ->where('t.id', $tag_id)
                    ->select('pivot.bookmark_id');
            })->paginate();

        $days = $this->toGroupedList($paging);

        return view('bookmarks.home')->with(compact('days', 'paging', 'tag_id', 'related_to'));
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
     * @param int $min_tags
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getMostUsedTags(int $count = 25, int $min_tags = 5)
    {
        return $this->tag->newQuery()->join(
            DB::raw("(SELECT `pivot`.`tag_id`, COUNT(`pivot`.`tag_id`) AS `tag_count` FROM `bookmark_tags` as `pivot`
                LEFT JOIN `bookmarks` as `b` ON `b`.`id` = `pivot`.`bookmark_id`
                WHERE `b`.`visible` = 1
                GROUP BY `tag_id`) AS `tc`"),
            "tags.id", '=', 'tc.tag_id'
        )->where('tag_count', '>', $min_tags)->orderBy('tag_count', 'desc')->limit($count)->get();
    }

    /**
     * @param int $count
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getMostPopularAuthors(int $count = 25)
    {
        return $this->user->newQuery()->join('bookmarks', 'users.id', '=', 'bookmarks.user_id')->limit($count)
            ->groupBy('users.id', 'users.name')->where('bookmarks.visible', 1)->orderBy('bookmark_count', 'desc')
            ->get(['users.id', 'users.name', DB::raw('count(`bookmarks`.`id`) as bookmark_count')]);
    }
}