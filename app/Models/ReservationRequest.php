<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_id',
        'action',
        'message',
        'status',
        'admin_response'
    ];

    public function reservation()
    {
        // Assuming you have a Reservation model
        return $this->belongsTo(Reservation::class, 'tracking_id', 'tracking_id');
    }
}