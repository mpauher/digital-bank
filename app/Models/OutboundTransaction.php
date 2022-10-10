<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutboundTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'outbound_amount',
        'send_user_id',
        'receive_user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
