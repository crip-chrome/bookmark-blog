<?php namespace App\Http\Requests;

use Illuminate\Validation\Validator;

/**
 * Class ApiChangedBookmarkRequest
 * @package App\Http\Requests
 */
class ApiChangedBookmarkRequest extends FormRequest
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
            'id' => 'required|numeric',
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

        if (!$exists) {
            $message = trans('api-validation.bookmark_does_not_exists', ['id' => $page_id]);
            $v->errors()->add('id', $message);
        }
    }
}
