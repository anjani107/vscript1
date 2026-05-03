<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoProject extends Model
{
    protected $fillable = ['title', 'description', 'status'];

    public function scripts(): HasMany
    {
        return $this->hasMany(Script::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'       => 'gray',
            'in_progress' => 'yellow',
            'completed'   => 'green',
            default       => 'gray',
        };
    }
}
