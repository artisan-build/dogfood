<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property int $id
 * @property int $message_id
 * @property int $member_id
 * @property int $option_index
 * @property Carbon|null $created_at
 */
class PollVote extends Model
{
    public $timestamps = false;

    protected $table = 'bonfire_poll_votes';

    protected $guarded = [];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'option_index' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
