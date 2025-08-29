<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'changes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
