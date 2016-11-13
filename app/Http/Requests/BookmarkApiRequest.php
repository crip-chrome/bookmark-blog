<?php namespace App\Http\Requests;

use App\Bookmark;

/**
 * Class BookmarkApiRequest
 * @package App\Http\Requests
 */
trait BookmarkApiRequest
{
    /**
     * @return array
     */
    public function existsForUser()
    {
        $user_id = \Auth::user()->id;
        $page_id = $this->id;

        $bookmark_exists = Bookmark::where('user_id', $user_id)->where('page_id', $page_id)->count();

        return [!!$bookmark_exists, $page_id];
    }
}