<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfTreatment extends Model
{
    use HasFactory;

    protected $table = 'types_of_treatments';

    protected $fillable = [
        'name',
        'description',
        'is_the_healing_touch',
        'has_form',
    ];
}
