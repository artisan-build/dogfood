<?php

declare(strict_types=1);

use ArtisanBuild\Bonfire\Enums\RoomType;
use ArtisanBuild\Bonfire\Facades\Bonfire;
use ArtisanBuild\Bonfire\Models\Member;
use ArtisanBuild\Bonfire\Models\Room;
use ArtisanBuild\Bonfire\Support\UnreadTracker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

return new class extends Component
{
    public string $filter = 'all';

    public string $sort = 'alpha';

    #[On('bonfire-filter')]
    public function setFilter(string $value): void
    {
        $this->filter = in_array($value, ['all', 'unread'], true) ? $value : 'all';
        unset($this->visibleRooms);
    }

    #[On('bonfire-sort')]
    public function setSort(string $value): void
    {
        $this->sort = in_array($value, ['alpha', 'recent'], true) ? $value : 'alpha';
        unset($this->visibleRooms);
    }

    #[Computed]
    public function currentMember(): ?Member
    {
        return Bonfire::memberFor(auth()->user());
    }

    #[Computed]
    public function starredRoomIds(): array
    {
        $member = $this->currentMember();

        if ($member === null) {
            return [];
        }

        return DB::table('bonfire_starred_rooms')
            ->where('member_id', $member->id)
            ->pluck('room_id')
            ->all();
    }

    #[Computed]
    public function visibleRooms(): Collection
    {
        $tenantId = Bonfire::tenantId();
        $member = $this->currentMember();

        $query = Room::query()
            ->where(fn ($q) => $q->where('tenant_id', $tenantId))
            ->withMax('messages as last_message_at', 'created_at')
            ->orderByRaw('(type & ?) > 0', [RoomType::Archived->value]);

        if ($this->sort === 'recent') {
            $query->orderByRaw('COALESCE(last_message_at, created_at) DESC');
        } else {
            $query->orderBy('name');
        }

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
        $starredIds = $this->starredRoomIds;

        $rooms->each(function (Room $room) use ($tracker, $member, $starredIds): void {
            $room->setAttribute('has_unread', $tracker->hasUnread($room, $member));
            $room->setAttribute('is_starred', in_array($room->id, $starredIds, true));
        });

        if ($this->filter === 'unread') {
            return $rooms->filter(fn (Room $room) => (bool) $room->has_unread)->values();
        }

        return $rooms;
    }

    /**
     * @return Collection<int, Member>
     */
    #[Computed]
    public function directMessageMembers(): Collection
    {
        $tenantId = Bonfire::tenantId();
        $current = $this->currentMember();

        return Member::query()
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->when($current !== null, fn ($q) => $q->where('id', '!=', $current->id))
            ->orderBy('display_name')
            ->get(['id', 'display_name', 'avatar_url']);
    }

    public function openDm(int $memberId)
    {
        $current = $this->currentMember();
        $target = Member::query()->find($memberId);

        if ($current === null || $target === null || $current->id === $target->id) {
            return null;
        }

        $sortedIds = collect([$current->id, $target->id])->sort()->implode('-');
        $slug = 'dm-'.$sortedIds;

        $room = Room::query()
            ->where('slug', $slug)
            ->where('tenant_id', Bonfire::tenantId())
            ->first();

        if ($room === null) {
            $room = Room::query()->create([
                'tenant_id' => Bonfire::tenantId(),
                'name' => $target->display_name,
                'slug' => $slug,
                'type' => RoomType::Private->value,
                'created_by' => $current->id,
            ]);

            $room->addMember($current);
            $room->addMember($target);
        }

        return $this->redirect(route('bonfire.room.show', $room), navigate: true);
    }

    public function toggleStar(int $roomId): void
    {
        $member = $this->currentMember();

        if ($member === null) {
            return;
        }

        $exists = DB::table('bonfire_starred_rooms')
            ->where('member_id', $member->id)
            ->where('room_id', $roomId)
            ->exists();

        if ($exists) {
            DB::table('bonfire_starred_rooms')
                ->where('member_id', $member->id)
                ->where('room_id', $roomId)
                ->delete();
        } else {
            DB::table('bonfire_starred_rooms')->insert([
                'member_id' => $member->id,
                'room_id' => $roomId,
                'created_at' => now(),
            ]);
        }

        unset($this->starredRoomIds, $this->visibleRooms);
    }
};
