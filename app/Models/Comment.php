<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'confession_id',
        'body',
        'ip_hash',
    ];

    public function confession(): BelongsTo
    {
        return $this->belongsTo(Confession::class);
    }
}
