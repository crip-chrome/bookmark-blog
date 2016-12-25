<?php namespace App\Http\Controllers;

use App\Bookmark;
use App\Category;
use App\Tag;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
     * @var Category
     */
    private $category;

    /**
     * BookmarksController constructor.
     * @param Bookmark $bookmark
     * @param Tag $tag
     * @param User $user
     * @param Category $category
     */
    public function __construct(Bookmark $bookmark, Tag $tag, User $user, Category $category)
    {
        $this->bookmark = $bookmark;
        $this->tag = $tag;
        $this->user = $user;
        $this->category = $category;

        View::share([
            'tags' => $this->getMostUsedTags(),
            'authors' => $this->getMostPopularAuthors(),
            'categories' => $this->getMostUsedCategories()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // t - tags
        // a - authors
        // c - categories
        $filters = $request->only('t', 'a', 'c');

        $query = $this->bookmark->newQuery()->where('url', '<>', '')->where('visible', true)
            ->with([
                'user' => function ($query) {
                    $query->select(['id', 'name']);
                },
                'tags' => function ($query) {
                    $query->select(['tags.id', 'tags.tag']);
                },
                'category' => function ($query) {
                    $query->select(['id', 'title']);
                }
            ])->orderBy('date_added', 'desc');

        // adding filters for authors
        if ($filters['a']) {
            $query = $query->whereIn('user_id', $filters['a']);
        }

        // adding filters for categories
        if ($filters['c']) {
            $query = $query->whereIn('category_id', $filters['c']);
        }

        // adding filters for tags
        if ($filters['t']) {
            $tags = $filters['t'];
            $query = $query->whereIn('id', function ($q) use ($tags) {
                $q->from('bookmark_tags AS pivot')
                    ->join('tags AS t', 't.id', '=', 'pivot.tag_id')
                    ->whereIn('t.id', $tags)
                    ->select('pivot.bookmark_id');
            });
        }

        $paging = $query->paginate()->appends($request->except('page'));
        $days = $this->toGroupedList($paging);

        return view('bookmarks.home')->with(compact('days', 'paging', 'filters'));
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
     * @param int $min_usages
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getMostUsedTags(int $count = 25, int $min_usages = 5)
    {
        return $this->tag->newQuery()->join(
            DB::raw("(SELECT `pivot`.`tag_id`, COUNT(`pivot`.`tag_id`) AS `tag_count` FROM `bookmark_tags` as `pivot`
                LEFT JOIN `bookmarks` as `b` ON `b`.`id` = `pivot`.`bookmark_id`
                WHERE `b`.`visible` = 1
                GROUP BY `tag_id`) AS `tc`"),
            'tags.id', '=', 'tc.tag_id'
        )->where('tag_count', '>', $min_usages)->orderBy('tag_count', 'desc')->limit($count)->get();
    }

    /**
     * @param int $count
     * @param int $min_bookmarks
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getMostPopularAuthors(int $count = 25, int $min_bookmarks = 10)
    {
        return $this->user->newQuery()->join('bookmarks', 'users.id', '=', 'bookmarks.user_id')->limit($count)
            ->where('bookmarks.visible', 1)->having(DB::raw('count(`bookmarks`.`id`)'), '>', $min_bookmarks)
            ->orderBy('bookmark_count', 'desc')->groupBy('users.id', 'users.name')
            ->get(['users.id', 'users.name', DB::raw('count(`bookmarks`.`id`) as `bookmark_count`')]);
    }

    /**
     * @param int $count
     * @param int $min_usages
     * @return Collection
     */
    private function getMostUsedCategories(int $count = 25, int $min_usages = 5)
    {
        $categories = $this->category->newQuery()->join('bookmarks', 'bookmarks.category_id', '=', 'categories.id')
            ->select(['categories.id', 'categories.title', DB::raw('COUNT(`bookmarks`.`id`) AS `usages`')])
            ->where('bookmarks.visible', 1)//// ->where('usages', '>', $min_usages) // sql error on where
            ->groupBy('categories.id', 'categories.title')->orderBy('usages', 'desc')->limit($count)->get();

        return $categories->filter(function ($item) use ($min_usages) {
            return $item->usages > $min_usages;
        });
    }
}