<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mention extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'bonfire_mentions';

    protected $primaryKey = null;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
