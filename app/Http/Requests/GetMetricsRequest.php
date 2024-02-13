<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Enums\MetricCategoryEnum;

class GetMetricsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
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
        return [
            '_token'   => 'required',
            'url'      => 'required|string|max:255',
            'strategy' => 'required|string|max:255|exists:strategies,name',
            'categories' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $category) {
                        if (!MetricCategoryEnum::isValidCategory($category)) {
                            $fail("The selected $attribute is invalid.");
                        }
                    }
                },
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
