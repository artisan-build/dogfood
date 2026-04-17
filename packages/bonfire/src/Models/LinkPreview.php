<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 * @mixin IdeHelperLinkPreview
 */
class LinkPreview extends Model
{
    public $timestamps = false;

    protected $table = 'bonfire_link_previews';

    protected $guarded = [];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'failed' => 'boolean',
            'fetched_at' => 'datetime',
        ];
    }
}
