<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const STATUS_SUCCEED = 'Succeed';
    const STATUS_PENDING = 'Pending';
    const STATUS_FAILED = 'Failed';

    protected $fillable = [
        'booking_id',
        'payment_date',
        'amount',
        'status',
    ];

    protected $table = 'payment';

    public function bookings()
    {
        return $this->belongsTo(Booking::class);
    }
}
