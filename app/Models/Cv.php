<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['user_id', 'filename', 'original_name', 'mime', 'size'])]
class Cv extends Model
{
    protected static function booted(): void
    {
        static::deleted(function (Cv $cv) {
            Storage::disk('local')->delete($cv->filename);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
