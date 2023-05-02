<?php

namespace App\Api\V1\Requests\ArticleSections;

use App\Enums\ArticleSectionTypes;
use BenSampo\Enum\Rules\EnumValue;
use Dingo\Api\Http\FormRequest;

class CreateArticleSectionRequest extends FormRequest
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
            'label' => 'required|string|min:2',
            'template' => 'required|string|min:2',
            'schema'      => 'required|json',
            'type' => ['required', new EnumValue(ArticleSectionTypes::class)]
        ];

        return $rules;
    }
}
