<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    const TYPE_ONE = 'One';
    const TYPE_STUDIO = 'Studio';
    const TYPE_TWO = 'Two';

    protected $table = 'room';

    protected $fillable = [
        'number',
        'name',
        'price',
        'type',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id');
    }
}
