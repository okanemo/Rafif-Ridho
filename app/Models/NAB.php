<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NAB extends Model
{
    use HasFactory;

    protected $fillable =[
        'nab'
    ];

    protected $table = 'NAB';
    protected $primaryKey = 'nab_id';
}