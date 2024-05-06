<?php

namespace App\Models;

use App\Models\Patient;
use App\Models\SpiritistCenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'number',
        'neighborhood',
        'zip_code',
        'state',
        'city',
    ];


    public function patient()
    {
        return $this->hasOne(Patient::class);
    }


    public function spiritistCenter()
    {
        return $this->hasOne(SpiritistCenter::class);
    }
}
