<?php namespace App;

use App\Http\Requests\FormRequest;
use Carbon\Carbon;
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
    public function __construct()
    {
        $num_args = func_num_args();
        $attributes = [];

        if ($num_args == 1 && gettype(func_get_arg(0)) == 'array') {
            $attributes = func_get_arg(0);
        } elseif ($num_args == 1 && get_parent_class(func_get_arg(0)) == FormRequest::class) {
            /** @var FormRequest $request */
            $request = func_get_arg(0);

            $attributes['parent_id'] = $request->parentId;
            $attributes['page_id'] = $request->id;
            $attributes['date_added'] = $request->dateAdded;
            $attributes['title'] = $request->title;
            $attributes['url'] = $request->url;
            $attributes['index'] = $request->index;
            $attributes['old_index'] = $request->oldIndex;
            $attributes['old_parent_id'] = $request->oldParentId;
            $attributes['user_id'] = \Auth::user()->id;
        }

        parent::__construct($attributes);
    }

    public function setDateAddedAttribute($value)
    {
        $dt = new Carbon();
        $dt->timestamp = $value / 1000;
        $this->attributes['date_added'] = $dt->format('Y-m-d H:i:s');
    }
}