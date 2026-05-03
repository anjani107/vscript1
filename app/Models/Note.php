<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = ['video_project_id', 'title', 'content', 'color'];

    public function videoProject(): BelongsTo
    {
        return $this->belongsTo(VideoProject::class);
    }

    public function getColorClassesAttribute(): array
    {
        return match ($this->color) {
            'blue'   => ['bg' => 'bg-blue-950',   'border' => 'border-blue-700',   'badge' => 'bg-blue-800 text-blue-200'],
            'green'  => ['bg' => 'bg-green-950',  'border' => 'border-green-700',  'badge' => 'bg-green-800 text-green-200'],
            'pink'   => ['bg' => 'bg-pink-950',   'border' => 'border-pink-700',   'badge' => 'bg-pink-800 text-pink-200'],
            'purple' => ['bg' => 'bg-purple-950', 'border' => 'border-purple-700', 'badge' => 'bg-purple-800 text-purple-200'],
            default  => ['bg' => 'bg-yellow-950', 'border' => 'border-yellow-700', 'badge' => 'bg-yellow-800 text-yellow-200'],
        };
    }
}
