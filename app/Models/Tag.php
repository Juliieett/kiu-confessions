<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Many-to-many: a tag can belong to many confessions.
     */
    public function confessions(): BelongsToMany
    {
        return $this->belongsToMany(Confession::class);
    }
}
