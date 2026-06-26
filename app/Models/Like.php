<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    protected $fillable = [
        'confession_id',
        'user_id',
        'ip_hash',
    ];

    public function confession(): BelongsTo
    {
        return $this->belongsTo(Confession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
