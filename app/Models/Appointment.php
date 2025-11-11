<?php

namespace App\Models;

use App\Models\AppointmentImage;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'customer_id',
        'service',
        'description',
        'signature_base64',
        'appointment_at',
        'is_done',
    ];

        // app/Models/Appointment.php
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function images()
    {
        return $this->hasMany(AppointmentImage::class);
    }
}

