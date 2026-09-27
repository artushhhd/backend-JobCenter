<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UploadCvRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->status === 'job_seeker';
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException('Only job seekers can upload a resume.');
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Attach a PDF, DOC or DOCX file.',
            'file.max' => 'Keep the resume under 5 MB.',
        ];
    }
}
