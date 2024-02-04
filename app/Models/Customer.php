<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    protected $table = 'customer';

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
