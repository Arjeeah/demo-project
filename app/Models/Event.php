<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
      use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'venue_id',
        'created_by',
    ];
      // Event belongs to a manager (User)
    public function manager()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Event belongs to a Venue
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    // Event has many Sponsors (Many-to-Many)
    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class, 'event_sponsor');
    }

    // Event has many Attendees (Users) through Tickets (Many-to-Many)
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'tickets');
    }

    // Event has many Comments (Polymorphic)
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
