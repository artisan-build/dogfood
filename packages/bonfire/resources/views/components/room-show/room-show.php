<?php

declare(strict_types=1);

use ArtisanBuild\Bonfire\Enums\BonfireRole;
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
    public Room $room;

    public ?int $openThreadId = null;

    public function mount(Room $room): void
    {
        $this->room = $room;

        abort_unless($this->canView(), 403);

        resolve(UnreadTracker::class)->markRead($room, $this->currentMember());
    }

    #[Computed]
    public function currentMember()
    {
        return Bonfire::memberFor(auth()->user());
    }

    #[Computed]
    public function canPost(): bool
    {
        $member = $this->currentMember();

        if ($member === null || ! $member->is_active) {
            return false;
        }

        if ($this->room->isArchived()) {
            return false;
        }

        if ($this->room->isAnnouncements()) {
            return $member->hasRoleAtLeast(BonfireRole::Moderator);
        }

        return $this->room->isAccessibleBy($member);
    }

    #[On('thread-open')]
    public function openThread(int $messageId): void
    {
        $this->openThreadId = $messageId;
    }

    #[On('thread-close')]
    public function closeThread(): void
    {
        $this->openThreadId = null;
    }

    /**
     * @return Collection<int, Member>
     */
    #[Computed]
    public function channelMembers(): Collection
    {
        return $this->room->members()
            ->orderBy('display_name')
            ->get([
                'bonfire_members.id',
                'display_name',
                'avatar_url',
                'memberable_type',
                'memberable_id',
                'phone',
                'timezone',
                'is_away',
                'status_emoji',
                'status_text',
            ]);
    }

    #[On('bonfire:member-updated')]
    public function onMemberUpdated(): void
    {
        unset($this->channelMembers);
    }

    #[Computed]
    public function isStarred(): bool
    {
        $member = $this->currentMember();

        if ($member === null) {
            return false;
        }

        return DB::table('bonfire_starred_rooms')
            ->where('member_id', $member->id)
            ->where('room_id', $this->room->id)
            ->exists();
    }

    public function toggleStar(): void
    {
        $member = $this->currentMember();

        if ($member === null) {
            return;
        }

        $exists = DB::table('bonfire_starred_rooms')
            ->where('member_id', $member->id)
            ->where('room_id', $this->room->id)
            ->exists();

        if ($exists) {
            DB::table('bonfire_starred_rooms')
                ->where('member_id', $member->id)
                ->where('room_id', $this->room->id)
                ->delete();
        } else {
            DB::table('bonfire_starred_rooms')->insert([
                'member_id' => $member->id,
                'room_id' => $this->room->id,
                'created_at' => now(),
            ]);
        }

        unset($this->isStarred);

        $this->dispatch('bonfire:star-toggled');
    }

    public function leaveChannel()
    {
        $member = $this->currentMember();

        if ($member === null || $this->room->isPrivate() === false) {
            return null;
        }

        $this->room->removeMember($member);

        return $this->redirect(route('bonfire.index'), navigate: true);
    }

    protected function canView(): bool
    {
        $member = Bonfire::memberFor(auth()->user());

        if (! $this->room->isPrivate()) {
            return true;
        }

        if ($member === null) {
            return false;
        }

        return $this->room->isAccessibleBy($member);
    }
};
