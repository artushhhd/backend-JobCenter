<?php

namespace App\Http\Requests;

use App\Models\Job;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
{
    private const MAX_SALARY = 100000000;

    public function authorize(): bool
    {
        return $this->user()->can('create', Job::class);
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException('Only recruiter accounts can manage job listings.');
    }

    protected function prepareForValidation(): void
    {
        $clean = [];

        foreach ($this->only($this->textFields()) as $field => $value) {
            $clean[$field] = is_string($value) && trim($value) !== '' ? trim($value) : null;
        }

        foreach ($this->only($this->listFields()) as $field => $value) {
            if (is_array($value)) {
                $clean[$field] = array_values(array_filter(array_map(
                    fn ($item) => is_string($item) ? trim($item) : $item,
                    $value
                ), fn ($item) => $item !== null && $item !== ''));
            }
        }

        $this->merge($clean);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'company' => ['required', 'string', 'max:120'],
            'department' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:80'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'internship'])],
            'experience_level' => ['required', Rule::in(['entry', 'mid', 'senior', 'lead'])],
            'description' => ['required', 'string', 'min:30', 'max:5000'],
            'responsibilities' => ['nullable', 'array', 'max:20'],
            'responsibilities.*' => ['required', 'string', 'max:300'],
            'requirements' => ['nullable', 'array', 'max:20'],
            'requirements.*' => ['required', 'string', 'max:300'],
            'skills' => ['nullable', 'array', 'max:10'],
            'skills.*' => ['required', 'string', 'max:40'],
            'salary_min' => ['nullable', 'integer', 'min:0', 'max:'.self::MAX_SALARY],
            'salary_max' => [
                'nullable',
                'integer',
                'min:0',
                'max:'.self::MAX_SALARY,
                Rule::when($this->filled('salary_min'), ['gte:salary_min']),
            ],
            'pay_period' => ['required', Rule::in(['hourly', 'daily', 'weekly', 'monthly', 'annual'])],
            'show_salary_range' => ['required', 'boolean'],
            'work_mode' => ['required', Rule::in(['onsite', 'hybrid', 'remote'])],
            'location' => [
                Rule::when($this->input('work_mode') === 'remote', ['nullable']),
                Rule::when($this->input('work_mode') !== 'remote', ['required']),
                'string',
                'max:120',
            ],
            'location_note' => ['nullable', 'string', 'max:120'],
            'application_method' => ['required', Rule::in(['email', 'link', 'platform'])],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'publish_to_marketplace' => ['required', 'boolean'],
            'notify_matching_candidates' => ['required', 'boolean'],
        ];
    }

    private function textFields(): array
    {
        return [
            'title',
            'company',
            'department',
            'category',
            'description',
            'location',
            'location_note',
            'deadline',
        ];
    }

    private function listFields(): array
    {
        return ['responsibilities', 'requirements', 'skills'];
    }
}
