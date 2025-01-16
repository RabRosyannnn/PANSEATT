<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'contact_information',
        'date',
        'start_time',
        'end_time',
        'number_of_guests',
        'booking_confirmation',
        'deposit',
        'occasion',
        'bundle',
        'note',
        'tracking_id',
        'price',
    ];
    public function bundles()
    {
        return $this->belongsToMany(Bundle::class);
    }
}