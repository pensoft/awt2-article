<?php

namespace App\Api\V1\Requests\ArticleSections;

use Dingo\Api\Http\FormRequest;

class UpdateArticleSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'name' => 'required|string|max:255',
            'label' => 'required|string|min:2',
            'template' => 'required|string|min:2',
        ];

        return $rules;
    }
}
