<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'capacity'];
     // A Venue can host multiple Events.
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Venue can have many Comments (Polymorphic).
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
