<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CvResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'original_name' => $this->original_name,
            'mime' => $this->mime,
            'size' => (int) $this->size,
            'uploaded_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
