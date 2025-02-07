<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Models;

use ArtisanBuild\Adverbs\States\DummyState;
use ArtisanBuild\Adverbs\Traits\GetsRowsFromVerbsStates;
use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null $name
 * @property string|null $description
 * @property string|null $metadata
 * @property int|null $id
 * @property string|null $last_event_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dummy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dummy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dummy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dummy whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dummy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dummy whereLastEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dummy whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dummy whereName($value)
 *
 * @mixin \Eloquent
 */
class Dummy extends Model
{
    use GetsRowsFromVerbsStates;
    use HasVerbsState;

    public string $stateClass = DummyState::class;

    protected function getStateClass(): string
    {
        return \Thunk\Verbs\State::class;
    }
}
