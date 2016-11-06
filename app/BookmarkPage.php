<?php namespace App;


class BookmarkPage extends BookmarkBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookmark_pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'page_id',
        'date_added',
        'title',
        'url'];
}