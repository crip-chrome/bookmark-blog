<?php namespace App\Http\Requests;

/**
 * Class StoreCategoryRequest
 * @package App\Http\Requests
 */
class StoreCategoryRequest extends FormRequest
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
            'title' => 'required|min:3|max:255|unique:categories,title'
        ];
    }

}