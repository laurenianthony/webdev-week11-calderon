<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Venue extends Model
{
    protected $fillable = ['name', 'location'];

    // A venue hosts many events
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    // A venue can have many polymorphic notes
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
