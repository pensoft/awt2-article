<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class ReferenceDefinitionRequest extends FormRequest
{
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

    public function rules()
    {

        $rules = [
            'title' => 'required|string|min:2',
            'type' => 'required|string|min:2',
            'schema'      => 'required|json',
        ];

        return $rules;
    }
}
