<?php

namespace App\Modules\Management\CourseManagement\Course\Validations;

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
        // Determine if this is an update request or a create
        $isUpdate = $this->route('slug') !== null;

        // Image should be required on create, optional on update
        $imageRule = $isUpdate
            ? 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
            : 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';

        return [
            'course_category_id' => 'required | sometimes',
            'title' => 'required | sometimes',
            'image' => $imageRule,
            'intro_video' => 'required | sometimes',
            'published_at' => 'required | sometimes',
            'is_published' => 'required | sometimes',
            'what_is_this_course' => 'required | sometimes',
            'why_is_this_course' => 'required | sometimes',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'meta_title' => 'sometimes',
            'meta_description' => 'sometimes',
            'meta_keywords' => 'sometimes',
        ];
    }
}