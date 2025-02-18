<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Models;

use ArtisanBuild\Adverbs\States\DummyState;
use ArtisanBuild\Adverbs\Traits\GetsRowsFromVerbsStates;
use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Thunk\Verbs\State;

/**
 * @property string|null $name
 * @property string|null $description
 * @property string|null $metadata
 * @property int|null $id
 * @property string|null $last_event_id
 *
 * @method static Builder<static>|Dummy newModelQuery()
 * @method static Builder<static>|Dummy newQuery()
 * @method static Builder<static>|Dummy query()
 * @method static Builder<static>|Dummy whereDescription($value)
 * @method static Builder<static>|Dummy whereId($value)
 * @method static Builder<static>|Dummy whereLastEventId($value)
 * @method static Builder<static>|Dummy whereMetadata($value)
 * @method static Builder<static>|Dummy whereName($value)
 *
 * @mixin Eloquent
 */
class Dummy extends Model
{
    use GetsRowsFromVerbsStates;
    use HasVerbsState;

    public string $state_class = DummyState::class;

    protected function getStateClass(): string
    {
        return State::class;
    }
}
