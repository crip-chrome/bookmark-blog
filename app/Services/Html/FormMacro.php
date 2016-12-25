<?php namespace App\Services\Html;

use App\Category;
use App\Tag;
use App\User;
use Form;
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
        $this->authorLink();
        $this->tagLink();
        $this->categoryLink();
        $this->tagList();
        $this->categoryList();
    }

    /**
     * Creates html helper macro for author link
     */
    private function authorLink()
    {
        Form::macro('authorLink', function (User $author, int $active_id = 0, $class = 'label label-info',
                                            $active_class = 'label label-primary') {

            $href = route('author', ['author_id' => $author->id]);
            $text = $author->name;

            if ($active_id == $author->id)
                return sprintf('<span class="%s">%s</span> ', $active_class, $text);

            return sprintf('<a href="%s" class="%s" title="%s">%s</a>', $href, $class, $text, $text);
        });
    }

    /**
     * Creates html helper macro for tag link
     */
    private function tagLink()
    {
        Form::macro('tagLink', function (Tag $model, $is_active = false, $class = 'label label-info',
                                         $active_class = 'label label-primary') {

            $href = route('tag', ['tag_id' => $model->id]);
            $text = $model->tag;
            if ($is_active)
                return sprintf('<span class="%s">%s</span> ', $active_class, $text);

            return sprintf('<a href="%s" class="%s" title="%s">%s</a> ', $href, $class, $text, $text);
        });
    }

    /**
     * Creates html helper macro for category link
     */
    private function categoryLink()
    {
        Form::macro('categoryLink', function (Category $model, $is_active = false, $class = 'label label-info',
                                         $active_class = 'label label-primary') {

            $href = route('category', ['category_id' => $model->id]);
            $text = $model->title;
            if ($is_active)
                return sprintf('<span class="%s">%s</span> ', $active_class, $text);

            return sprintf('<a href="%s" class="%s" title="%s">%s</a> ', $href, $class, $text, $text);
        });
    }

    /**
     * Creates html helper macro for tag link list
     */
    private function tagList()
    {
        Form::macro('tagList', function (Collection $models, int $active_id = 0, $class = 'label label-info',
                                         $active_class = 'label label-primary') {

            $list = '';
            foreach ($models as $model) {
                $is_active = $active_id == $model->id;
                $list .= Form::tagLink($model, $is_active, $class, $active_class);
            }

            return $list;
        });
    }

    /**
     * Creates html helper macro for category link list
     */
    private function categoryList()
    {
        Form::macro('categoryList', function (Collection $models, int $active_id = 0, $class = 'label label-info',
                                              $active_class = 'label label-primary') {

            $list = '';
            foreach ($models as $model) {
                $is_active = $active_id == $model->id;
                $list .= Form::categoryLink($model, $is_active, $class, $active_class);
            }

            return $list;
        });
    }
}