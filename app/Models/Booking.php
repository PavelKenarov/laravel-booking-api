<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id',
        'room_id',
        'start_date',
        'end_date',
        'price',
    ];

    protected $table = 'booking';

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }

    public function rooms()
    {
        return $this->belongsTo(Room::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }
}
