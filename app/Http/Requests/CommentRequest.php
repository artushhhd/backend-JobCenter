<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $comment = $this->route('comment');

        return $comment
            ? $this->user()->can('update', $comment)
            : $this->user()->can('create', Comment::class);
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException('You can only edit your own comment.');
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('body'))) {
            $this->merge(['body' => trim((string) $this->input('body'))]);
        }
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:2', 'max:2000'],
        ];
    }
}
