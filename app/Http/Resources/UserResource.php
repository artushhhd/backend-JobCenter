<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'role' => $this->role,
            'cv' => $this->whenLoaded('cv', fn () => $this->cv ? new CvResource($this->cv) : null),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
