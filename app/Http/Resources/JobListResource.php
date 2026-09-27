<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class JobListResource extends JobResource
{
    public function toArray(Request $request): array
    {
        $attributes = parent::toArray($request);

        unset($attributes['responsibilities'], $attributes['requirements']);

        return $attributes;
    }
}
