<?php namespace App\Http\Requests;

use App\Bookmark;
use Illuminate\Validation\Validator;

/**
 * Class ApiCreatedBookmarkRequest
 * @package App\Http\Requests
 */
class ApiCreatedBookmarkRequest extends FormRequest
{
    use BookmarkApiRequest;

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
     * @param Validator $v
     */
    public function after(Validator $v)
    {
        list($exists, $page_id) = $this->existsForUser();

        if ($exists) {
            $message = trans('api-validation.bookmark_exists', ['id' => $page_id]);
            $v->errors()->add('id', $message);
        }
    }
}
