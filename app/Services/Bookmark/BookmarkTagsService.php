<?php namespace App\Services\Bookmark;

use App\Bookmark;
use App\Tag;

/**
 * Class BookmarkTagsService
 * @package App\Services\Bookmark
 */
class BookmarkTagsService
{
    /**
     * @param Bookmark $bookmark
     */
    public function syncTags(Bookmark $bookmark)
    {
        $page_id = $bookmark->page_id;
        $tags = $this->relatedTags($page_id);
        $ids = collect($tags)->map(function ($tag) use ($page_id) {
            // do not create tag from self
            if ($tag->page_id == $page_id)
                return null;

            // tag already exists, return it id
            if ($tag->tag_id)
                return $tag->tag_id;

            // create new tag and return created id
            return Tag::create(['tag' => $tag->title])->id;
        });

        $ids = $ids->filter(function ($tag_id) {
            // get rid of empty values
            return !!$tag_id;
        })->toArray();

        $bookmark->tags()->sync($ids);
    }

    /**
     * Get all related/unrelated tags for bookmark
     *
     * @param int $page_id
     * @return array
     */
    private function relatedTags(int $page_id)
    {
        $bookmark_table = (new Bookmark())->getTable();
        $tag_table = (new Tag())->getTable();

        $tags = \DB::select("
            SELECT B.id, B.page_id, B.title, T.id as tag_id
            FROM (
                SELECT
                    @r AS _id,
                    (SELECT @r := parent_id FROM $bookmark_table WHERE page_id = _id) AS parent_id,
                    (SELECT user_id FROM $bookmark_table WHERE page_id = _id) as user_id,
                    @l := @l + 1 AS lvl
                FROM
                    (SELECT @r := ?, @l := 0) vars,
                    $bookmark_table h
                WHERE @r <> 0 AND @r <> 1) C
            JOIN $bookmark_table B ON C._id = B.page_id AND C.user_id = B.user_id
            LEFT JOIN $tag_table T ON T.tag = B.title
            ORDER BY C.lvl DESC
        ", [$page_id]);

        return $tags;
    }
}