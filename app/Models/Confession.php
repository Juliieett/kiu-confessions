<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Confession Model
 *
 * Represents an anonymous confession submitted by a KIU student.
 *
 * @property int         $id
 * @property string      $title        Short subject/category of the confession
 * @property string      $description  The full confession text
 * @property string      $status       pending | approved | rejected
 * @property int|null    $category_id  Foreign key to categories table
 * @property \Carbon\Carbon|null $deadline  Admin review-by date (required by rubric)
 * @property string|null $image_path   Optional uploaded image path
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
        'category_id',
        'referenced_confession_id',
        'deadline',
        'ip_hash',
        'image_path',
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

    // ─── Relationships ─────────────────────────────────────────────────────────

    /**
     * Many confessions belong to one category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Many-to-many: a confession can have many tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Confession this post is replying to (optional).
     */
    public function referencedConfession(): BelongsTo
    {
        return $this->belongsTo(Confession::class, 'referenced_confession_id');
    }

    /**
     * Other confessions that reference this post.
     */
    public function referencingConfessions(): HasMany
    {
        return $this->hasMany(Confession::class, 'referenced_confession_id');
    }

    /**
     * Likes on this confession.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Comments on this confession.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Public post number shown to students (same as database id).
     */
    public function postNumber(): string
    {
        return '#' . $this->id;
    }

    /**
     * Whether this post is liked by the current visitor.
     */
    public function isLikedBy(?int $userId, ?string $ipHash): bool
    {
        if ($userId) {
            return $this->likes()->where('user_id', $userId)->exists();
        }

        if (! $ipHash) {
            return false;
        }

        return $this->likes()->whereNull('user_id')->where('ip_hash', $ipHash)->exists();
    }

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
     * Public URL for the confession image, if one was uploaded.
     */
    public function imageUrl(): ?string
    {
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : null;
    }

    /**
     * Remove the stored image file from disk.
     */
    public function deleteImage(): void
    {
        if ($this->image_path) {
            Storage::disk('public')->delete($this->image_path);
        }
    }

}
