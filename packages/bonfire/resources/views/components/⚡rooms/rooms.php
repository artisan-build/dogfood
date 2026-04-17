<?php

declare(strict_types=1);

use ArtisanBuild\Bonfire\Enums\RoomType;
use ArtisanBuild\Bonfire\Facades\Bonfire;
use ArtisanBuild\Bonfire\Models\Room;
use ArtisanBuild\Bonfire\Support\UnreadTracker;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

return new class extends Component
{
    #[Computed]
    public function currentMember()
    {
        return Bonfire::memberFor(auth()->user());
    }

    #[Computed]
    public function visibleRooms(): Collection
    {
        $tenantId = Bonfire::tenantId();
        $member = $this->currentMember();

        $query = Room::query()
            ->where(fn ($q) => $q->where('tenant_id', $tenantId))
            ->orderByRaw('(type & ?) > 0', [RoomType::Archived->value])
            ->orderBy('name');

        if ($member === null || ! $member->is_active) {
            $rooms = $query->whereRaw('(type & ?) = 0', [RoomType::Private->value])->get();
        } else {
            $privateRoomIds = $member->rooms()->pluck('bonfire_rooms.id');

            $rooms = $query->where(function ($q) use ($privateRoomIds): void {
                $q->whereRaw('(type & ?) = 0', [RoomType::Private->value])
                    ->orWhereIn('id', $privateRoomIds);
            })->get();
        }

        $tracker = resolve(UnreadTracker::class);

        return $rooms->each(function (Room $room) use ($tracker, $member): void {
            $room->setAttribute('has_unread', $tracker->hasUnread($room, $member));
        });
    }
};
