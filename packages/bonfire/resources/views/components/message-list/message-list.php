<?php

declare(strict_types=1);

use ArtisanBuild\Bonfire\Enums\BonfireRole;
use ArtisanBuild\Bonfire\Facades\Bonfire;
use ArtisanBuild\Bonfire\Models\Member;
use ArtisanBuild\Bonfire\Models\Message;
use ArtisanBuild\Bonfire\Models\Room;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

return new class extends Component
{
    use WithPagination;

    public Room $room;

    public int $perPage = 40;

    public string $search = '';

    public function mount(Room $room): void
    {
        $this->room = $room;
    }

    #[On('bonfire-search')]
    public function setSearch(string $value): void
    {
        $this->search = trim($value);
        $this->resetPage();
        unset($this->messages);
    }

    #[Computed]
    public function messages(): CursorPaginator
    {
        $query = Message::query()
            ->with(['member', 'replies', 'attachments', 'linkPreview'])
            ->where('room_id', $this->room->getKey())
            ->whereNull('parent_id')
            ->where(fn ($q) => $q->whereNull('scheduled_for')->orWhere('scheduled_for', '<=', now()));

        if ($this->search !== '') {
            $needle = '%'.str_replace(['%', '_'], ['\%', '\_'], $this->search).'%';
            $query->where('body', 'like', $needle);
        }

        return $query->oldest()->cursorPaginate($this->perPage);
    }

    #[On('echo-presence:bonfire.room.{room.id},.message.posted')]
    public function onMessagePosted(): void
    {
        unset($this->messages);
    }

    #[On('echo-presence:bonfire.room.{room.id},.message.deleted')]
    public function onMessageDeleted(): void
    {
        unset($this->messages);
    }

    #[On('bonfire:member-updated')]
    public function onMemberUpdated(): void
    {
        unset($this->messages);
    }

    public function openThread(int $messageId): void
    {
        $this->dispatch('thread-open', messageId: $messageId);
    }

    #[Computed]
    public function currentMember(): ?Member
    {
        return Bonfire::memberFor(auth()->user());
    }

    public function canDelete(Message $message): bool
    {
        $member = $this->currentMember();

        if ($member === null || ! $member->is_active) {
            return false;
        }

        if ($message->member_id === $member->getKey()) {
            return true;
        }

        return $member->hasRoleAtLeast(BonfireRole::Moderator);
    }

    public function deleteMessage(int $messageId): void
    {
        $message = Message::query()->findOrFail($messageId);

        abort_unless($this->canDelete($message), 403);

        $message->delete();

        unset($this->messages);
    }
};
