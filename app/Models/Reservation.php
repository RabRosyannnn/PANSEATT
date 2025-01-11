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
        'time',
        'number_of_guests',
        'booking_confirmation',
        'deposit',
        'occasion',
        'bundle',
    ];
}