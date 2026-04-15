<?php

declare(strict_types=1);

use ArtisanBuild\Bonfire\Enums\BonfireRole;
use ArtisanBuild\Bonfire\Facades\Bonfire;
use ArtisanBuild\Bonfire\Models\Room;
use ArtisanBuild\Bonfire\Support\UnreadTracker;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
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
