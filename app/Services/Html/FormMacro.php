<?php namespace App\Services\Html;

use App\Category;
use App\Tag;
use App\User;
use Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class FormMacro
 * @package App\Services\Html
 */
class FormMacro
{
    /**
     * FormMacro constructor.
     */
    public function __construct()
    {
        $this->filter();
    }

    private function filter()
    {
        Form::macro('filter', function (Model $model = null, $key, $filters, $filters_key, $class = 'label label-info',
                                        $active_class = 'label label-primary') {
            if ($model === null)
                return '';

            $id = $model->id;

            if (!$filters)
                $filters = [];

            if (!isset($filters[$filters_key]))
                $filters[$filters_key] = [];

            if (in_array($id, $filters[$filters_key])) {
                $arr_key = array_search($id, $filters[$filters_key]);
                unset($filters[$filters_key][$arr_key]);
                $class = $active_class;
            } else
                $filters[$filters_key][] = $id;

            $href = route('home', $filters);
            $text = $model->$key;

            return sprintf('<a href="%s" class="%s" title="%s">%s</a>', $href, $class, $text, $text);
        });
    }
}