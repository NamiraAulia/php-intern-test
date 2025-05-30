<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees'; // Tentukan nama tabel jika tidak sesuai konvensi Laravel (plural dari nama model)

    protected $fillable = [
        'nomor',
        'nama',
        'jabatan',
        'talahir',
        'photo_upload_path',
        'created_on',
        'updated_on',
        'created_by',
        'updated_by',
    ];
    
}
