<?php

namespace App\Api\V1\Requests\Layouts;

use Dingo\Api\Http\FormRequest;

class UpdateLayoutRequest extends FormRequest
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
            'article_template_id' => 'required|exists:article_templates,id',
        ];

        return $rules;
    }
}
