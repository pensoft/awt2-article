<?php

namespace App\Api\V1\Requests\ArticleTemplates;

use Dingo\Api\Http\FormRequest;

class CreateArticleTemplateRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'schema' => json_encode($this->schema),
        ]);
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
            'schema'      => 'required|json',
        ];

        return $rules;
    }
}
