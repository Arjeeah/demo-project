<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
     use HasFactory;

    protected $fillable = ['name', 'description', 'logo_url'];
     // Many-to-Many: Sponsor supports many Events.
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_sponsor');
    }
}
