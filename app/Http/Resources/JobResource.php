<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'company' => $this->company,
            'department' => $this->department,
            'category' => $this->category,
            'employment_type' => $this->employment_type,
            'experience_level' => $this->experience_level,
            'description' => $this->description,
            'responsibilities' => $this->responsibilities ?? [],
            'requirements' => $this->requirements ?? [],
            'skills' => $this->skills ?? [],
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'pay_period' => $this->pay_period,
            'show_salary_range' => (bool) $this->show_salary_range,
            'work_mode' => $this->work_mode,
            'location' => $this->location,
            'location_note' => $this->location_note,
            'featured' => (bool) $this->featured,
            'application_method' => $this->application_method,
            'deadline' => $this->deadline?->format('Y-m-d'),
            'status' => $this->status,
            'publish_to_marketplace' => (bool) $this->publish_to_marketplace,
            'notify_matching_candidates' => (bool) $this->notify_matching_candidates,
            'comment_count' => (int) $this->comments_count,
            'like_count' => (int) $this->likes_count,
            'liked' => (bool) ($this->liked ?? false),
            'created_at' => $this->created_at?->toIso8601String(),
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
