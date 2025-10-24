<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'chart_type',
        'package_type',
        'data',
        'options',
        'colors',
        'file_path',
    ];

    protected $casts = [
        'data' => 'array',
        'options' => 'array',
        'colors' => 'array',
    ];
}
