<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ManagedJobResource extends JobResource
{
    public function toArray(Request $request): array
    {
        $attributes = parent::toArray($request);

        unset(
            $attributes['description'],
            $attributes['responsibilities'],
            $attributes['requirements'],
            $attributes['liked'],
        );

        return array_merge($attributes, [
            'author' => $this->authorPayload(),
            'editable' => $request->user()->can('update', $this->resource),
            'deletable' => $request->user()->can('delete', $this->resource),
        ]);
    }

    private function authorPayload(): ?array
    {
        if (! $this->relationLoaded('recruiter') || ! $this->recruiter) {
            return null;
        }

        return [
            'id' => $this->recruiter->id,
            'name' => $this->recruiter->name,
        ];
    }
}
