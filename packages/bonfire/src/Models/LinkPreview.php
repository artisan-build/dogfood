<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkPreview extends Model
{
    public $timestamps = false;

    protected $table = 'bonfire_link_previews';

    protected $guarded = [];

    protected $casts = [
        'failed' => 'boolean',
        'fetched_at' => 'datetime',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}
