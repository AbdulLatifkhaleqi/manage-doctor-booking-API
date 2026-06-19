<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //
      protected $fillable = [
        'userId',
        'docId',
        'slotDate',
        'slotTime',
        'cancelled',
        'payment',
        'isCompleted',
    ];
}
