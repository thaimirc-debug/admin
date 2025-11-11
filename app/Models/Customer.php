<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
   protected $fillable = [
        'name', 
        'address', 
        'province', 
        'phones',
        'packet',
        'start_date',
        'job_description',
        'price',
        'branch_id',
    ];

    protected $casts = [
        'phones' => 'array',
        'start_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class)->orderBy('appointment_at');
    }
}
