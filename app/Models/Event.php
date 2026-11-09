<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'details', 'start_time', 'venue_id'];

    protected $casts = [
        'details' => 'array',
        'start_time' => 'datetime',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class)
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_time', '>', now());
    }

    public function scopeMinCapacity(Builder $query, int $capacity): Builder
    {
        return $query->where('details->capacity', '>=', $capacity);
    }
}