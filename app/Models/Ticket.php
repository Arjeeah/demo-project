<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'user_id', 'purchase_date', 'seat_number'];
     // A Ticket belongs to an Event.
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // A Ticket belongs to a User (Attendee).
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
