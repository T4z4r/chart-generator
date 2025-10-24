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
        'data',
        'options',
        'file_path',
    ];

    protected $casts = [
        'data' => 'array',
        'options' => 'array',
    ];
}
