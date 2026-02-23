<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model
{
    use HasFactory;

    // Engedélyezzük ezen oszlopok írását
    protected $fillable = [
        'zip_code',
        'city',
    ];
}