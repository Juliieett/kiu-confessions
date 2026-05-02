<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Confession Model
 *
 * Represents an anonymous confession submitted by a KIU student.
 *
 * @property int         $id
 * @property string      $title        Short subject/category of the confession
 * @property string      $description  The full confession text
 * @property string      $status       pending | approved | rejected
 * @property string|null $category     Optional tag (Study, Crush, Funny, Other)
 * @property \Carbon\Carbon|null $deadline  Admin review-by date (required by rubric)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Confession extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'confessions';

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'category',
        'deadline',
    ];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'deadline' => 'date',
    ];

    // ─── Status Constants ──────────────────────────────────────────────────────

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // ─── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Only approved confessions (shown on the public feed).
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Only pending confessions (shown in admin panel).
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Only rejected confessions.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Returns a Bootstrap badge class based on current status.
     */
    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            default               => 'bg-warning text-dark',
        };
    }

    /**
     * Returns a human-readable status label.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            default               => 'Pending',
        };
    }

    /**
     * Available category options.
     */
    public static function categories(): array
    {
        return ['Study Life', 'Crush', 'Funny', 'Serious', 'Professor', 'Other'];
    }
}
