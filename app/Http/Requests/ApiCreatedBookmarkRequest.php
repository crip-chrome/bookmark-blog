<?php namespace App\Http\Requests;

use App\Bookmark;
use Illuminate\Validation\Validator;

/**
 * Class ApiCreatedBookmarkRequest
 * @package App\Http\Requests
 */
class ApiCreatedBookmarkRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dateAdded' => 'required|numeric',
            'index' => 'required|numeric',
            'id' => 'required|numeric',
            'parentId' => 'required|numeric',
            'title' => 'required',
            'url' => '',
        ];
    }

    /**
     * After Validation Hook
     *
     * @return void
     */
    public function after(Validator $v)
    {
        $user_id = \Auth::user()->id;
        $page_id = $this->id;

        $bookmark_exists = Bookmark::where('user_id', $user_id)->where('page_id', $page_id)->count();

        if ($bookmark_exists) {
            $message = trans('api-validation.bookmark_exists');
            $message = sprintf($message, $page_id);
            $v->errors()->add('id', $message);
        }
    }
}
