<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','category','desc','price', 'image','is_archived',
    ];
    public function reservations()
{
    return $this->belongsToMany(Reservation::class, 'reservation_bundle');
}

}

