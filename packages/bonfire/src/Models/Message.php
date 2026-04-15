<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $tenant_id
 * @property int $room_id
 * @property int $member_id
 * @property int|null $parent_id
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Message extends Model
{
    use SoftDeletes;

    protected $table = 'bonfire_messages';

    protected $guarded = [];

    protected $casts = [
        'tenant_id' => 'integer',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class, 'message_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'message_id');
    }

    public function linkPreview(): HasOne
    {
        return $this->hasOne(LinkPreview::class, 'message_id');
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(Mention::class, 'message_id');
    }

    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }
}
