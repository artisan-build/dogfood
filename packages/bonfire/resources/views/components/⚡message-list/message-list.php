<?php

declare(strict_types=1);

use ArtisanBuild\Bonfire\Models\Message;
use ArtisanBuild\Bonfire\Models\Room;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public Room $room;

    public int $perPage = 40;

    public function mount(Room $room): void
    {
        $this->room = $room;
    }

    #[Computed]
    public function messages(): CursorPaginator
    {
        return Message::query()
            ->with(['member', 'replies'])
            ->where('room_id', $this->room->getKey())
            ->whereNull('parent_id')
            ->orderBy('created_at')
            ->cursorPaginate($this->perPage);
    }

    #[On('echo:bonfire.room.{room.id},MessagePosted')]
    public function onMessagePosted(): void
    {
        unset($this->messages);
    }

    #[On('echo:bonfire.room.{room.id},MessageDeleted')]
    public function onMessageDeleted(): void
    {
        unset($this->messages);
    }

    public function openThread(int $messageId): void
    {
        $this->dispatch('thread-open', messageId: $messageId);
    }
};
