<?php namespace App;

/**
 * Class BookmarkFolder
 * @package App
 */
class BookmarkFolder extends BookmarkBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookmark_folders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'page_id',
        'date_added',
        'title'
    ];
}
