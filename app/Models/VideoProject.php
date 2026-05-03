<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoProject extends Model
{
    protected $fillable = ['user_id', 'client_id', 'title', 'description', 'status'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function scripts(): HasMany
    {
        return $this->hasMany(Script::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class)->latest();
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
