<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'treatment_type_id',
        'treatment_id',
        'date',
        'treatment_mode',
        'status',
        'notes',
        'who_requested_it',
        'who_requested_it_phone',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatmentType()
    {
        return $this->belongsTo(TypeOfTreatment::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
