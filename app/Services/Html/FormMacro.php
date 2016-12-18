<?php namespace App\Services\Html;

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
        $this->tagList();
    }

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

    private function tagLink()
    {
        Form::macro('tagLink', function (Tag $tag, $is_active = false, $class = 'label label-info',
                                         $active_class = 'label label-primary') {

            $href = route('tag', ['tag_id' => $tag->id]);
            $text = $tag->tag;
            if ($is_active)
                return sprintf('<span class="%s">%s</span> ', $active_class, $text);

            return sprintf('<a href="%s" class="%s" title="%s">%s</a> ', $href, $class, $text, $text);
        });
    }

    private function tagList()
    {
        Form::macro('tagList', function (Collection $tags, int $active_id = 0, $class = 'label label-info',
                                         $active_class = 'label label-primary') {

            $list = '';
            foreach ($tags as $tag) {
                $is_active = $active_id == $tag->id;
                $list .= Form::tagLink($tag, $is_active, $class, $active_class);
            }

            return $list;
        });
    }
}