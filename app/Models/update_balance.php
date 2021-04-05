<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class update_balance extends Model
{
    use HasFactory;
    protected $fillable = [
        'action',
        'amount',
        'user_id'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
