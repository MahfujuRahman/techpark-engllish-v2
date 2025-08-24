<?php

namespace App\Modules\Management\CourseManagement\CourseInstructors\Validations;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DataStoreValidation extends FormRequest
{
    /**
     * Determine if the  is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * validateError to make this request.
     */
    public function validateError($data)
    {
        $errorPayload =  $data->getMessages();
        return response(['status' => 'validation_error', 'errors' => $errorPayload], 422);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validateError($validator->errors()));
        if ($this->wantsJson() || $this->ajax()) {
            throw new HttpResponseException($this->validateError($validator->errors()));
        }
        parent::failedValidation($validator);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'cover_photo' => 'required | sometimes',
            'image' => 'required | sometimes',
            'full_name' => 'required | sometimes',
            'designation' => 'required | sometimes',
            'short_description' => 'required | sometimes',
            'description' => 'required | sometimes',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],

            'email' => 'required | sometimes | email',
            'phone' => 'required | sometimes | numeric',
            'facebook' => 'required | sometimes | url',
            'linkedin' => 'required | sometimes | url',
            'instagram' => 'required | sometimes | url',
            'twitter' => 'required | sometimes | url',
        ];
    }
}