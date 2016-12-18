<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Tag
 * @package App
 */
class Tag extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The related table name
     *
     * @var string
     */
    public static $bookmarks = 'bookmark_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tag'];

    /**
     * Related bookmarks
     *
     * @return BelongsToMany
     */
    public function bookmarks()
    {
        return $this->belongsToMany(Tag::class, static::$bookmarks, 'tag_id', 'bookmark_id');
    }
}
