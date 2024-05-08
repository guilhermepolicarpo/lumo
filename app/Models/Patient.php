<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_id',
        'name',
        'email',
        'phone',
        'birth',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
