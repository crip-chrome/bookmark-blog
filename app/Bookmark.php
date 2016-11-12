<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Bookmark
 * @package App
 */
class Bookmark extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookmarks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'page_id',
        'user_id',
        'date_added',
        'title',
        'url'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_added' => 'timestamp',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param Request $request
     * @param  array $attributes
     */
    public function __construct(Request $request, array $attributes = [])
    {
        $attributes['parent_id'] = $request->parentId;
        $attributes['page_id'] = $request->id;
        $attributes['date_added'] = $request->dateAdded;
        $attributes['title'] = $request->title;
        $attributes['url'] = $request->url;
        $attributes['index'] = $request->index;
        $attributes['old_index'] = $request->oldIndex;
        $attributes['old_parent_id'] = $request->oldParentId;
        $attributes['user_id'] = \Auth::user()->id;

        parent::__construct($attributes);
    }
}