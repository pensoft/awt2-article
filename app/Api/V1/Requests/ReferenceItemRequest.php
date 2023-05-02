<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class ReferenceItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'data' => json_encode($this->schema),
        ]);
    }

    public function rules()
    {
        $rules = [
            'title'                   => 'required|string|min:2',
            'data'                    => 'required|json',
            'reference_definition_id' => 'required|exists:reference_definitions,id',
        ];

        return $rules;
    }
}
