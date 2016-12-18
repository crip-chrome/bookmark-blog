<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'url',
        'visible'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_added' => 'datetime',
    ];

    public function setDateAddedAttribute($value)
    {
        $dt = new Carbon();
        $dt->timestamp = $value / 1000;
        $this->attributes['date_added'] = $dt->format('Y-m-d H:i:s');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeCurrentUser(Builder $query)
    {
        return $query->where('user_id', \Auth::user()->id);
    }

    /**
     * @param Builder $query
     * @param int $parent_id
     * @return Builder
     */
    public function scopeParent(Builder $query, $parent_id)
    {
        return $query->where('parent_id', $parent_id);
    }

    /**
     * @param Builder $query
     * @param int $page_id
     * @return Builder
     */
    public function scopePage(Builder $query, $page_id)
    {
        return $query->where('page_id', $page_id);
    }

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(Bookmark::class, 'parent_id', 'page_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Bookmark::class, 'parent_id', 'page_id');
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, Tag::$bookmarks, 'bookmark_id', 'tag_id');
    }
}