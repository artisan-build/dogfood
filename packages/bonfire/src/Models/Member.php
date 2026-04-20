<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Models;

use ArtisanBuild\Bonfire\Enums\BonfireRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property int $id
 * @property int|null $tenant_id
 * @property string $memberable_type
 * @property int $memberable_id
 * @property string $display_name
 * @property string|null $avatar_url
 * @property BonfireRole $role
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin IdeHelperMember
 */
class Member extends Model
{
    use Notifiable;

    protected $table = 'bonfire_members';

    protected $guarded = [];

    public function memberable(): MorphTo
    {
        return $this->morphTo();
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'bonfire_member_room', 'member_id', 'room_id')
            ->withPivot(['created_by', 'last_read_at', 'created_at']);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'member_id');
    }

    public function hasRoleAtLeast(BonfireRole $role): bool
    {
        return $this->role->hasAtLeast($role);
    }

    protected function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    protected function scopeForTenant(Builder $query, ?int $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'role' => BonfireRole::class,
            'tenant_id' => 'integer',
        ];
    }
}
