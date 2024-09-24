<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ScheduleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'timezone' => 'required',
        ];
    }

        /**
     * Handle a failed validation attempt.
     * @param \Illuminate\Contracts\Validation\Validator $validator The validation instance.
     * @return void
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Thrown to interrupt the normal response process.
     */
    protected function failedValidation($validator)
    {
        $errors = $validator->errors()->toArray();
        throw new HttpResponseException(ApiResponse::error('The data was invalid', $errors));
    }
}
