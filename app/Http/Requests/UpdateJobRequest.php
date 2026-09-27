<?php

namespace App\Http\Requests;

class UpdateJobRequest extends StoreJobRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('job'));
    }
}
