<?php

declare(strict_types=1);

namespace ArtisanBuild\Till\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TillSubscription extends Model
{
    protected $fillable = [
        'team_id',
        'plan_id',
        'renews_at',
        'expires_at',
    ];

    protected $casts = [
        'renews_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(TillTeam::class);
    }
}
