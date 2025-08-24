<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    protected $fillable = [
        'name',
        'phone',
        'license_type',
        'latitude',
        'longitude',
        'address',
        'expected_salary',
        'image',
        'status',
        'rating',
    ];

    public $timestamps = false; // if table has no created_at/updated_at
}
