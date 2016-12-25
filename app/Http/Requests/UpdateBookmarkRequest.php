<?php namespace App\Http\Requests;

/**
 * Class UpdateBookmarkRequest
 * @package App\Http\Requests
 */
class UpdateBookmarkRequest extends FormRequest
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
            'id' => 'required|exists:bookmarks,id',
            'visible' => 'required|boolean',
            'category_id' => 'required|exists:categories,id'
        ];
    }
}