<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;

class ManagedUserResource extends UserResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'rank' => User::ROLE_RANKS[$this->role] ?? User::RANK_USER,
            'job_count' => (int) ($this->jobs_count ?? 0),
            'comment_count' => (int) ($this->comments_count ?? 0),
            'deletable' => $request->user()->can('delete', $this->resource),
        ]);
    }
}
