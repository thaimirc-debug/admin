<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentImage extends Model
{
    protected $fillable = [
        'appointment_id','path'
    ];
    
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
