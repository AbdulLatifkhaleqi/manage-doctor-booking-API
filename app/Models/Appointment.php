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

    public function user()
{
    return $this->belongsTo(User::class, 'userId');
}

public function doctor()
{
    return $this->belongsTo(Doctor::class, 'docId');
}
}
