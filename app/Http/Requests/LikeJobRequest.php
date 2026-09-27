<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class LikeJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('like', $this->route('job'));
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException('This job can no longer be liked.');
    }

    public function rules(): array
    {
        return [];
    }
}
