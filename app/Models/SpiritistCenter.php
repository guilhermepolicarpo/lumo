<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;

class SpiritistCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address_id',
        'email',
        'phone',
        'logo_url',
    ];


    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
