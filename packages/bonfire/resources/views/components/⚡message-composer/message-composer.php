<?php

declare(strict_types=1);

use ArtisanBuild\Bonfire\Facades\Bonfire;
use ArtisanBuild\Bonfire\Models\Member;
use ArtisanBuild\Bonfire\Models\Message;
use ArtisanBuild\Bonfire\Models\Room;
use Livewire\Attributes\Computed;
use Livewire\Component;

return new class extends Component
{
    public Room $room;

    public ?int $parentId = null;

    public string $body = '';

    public function mount(Room $room, ?int $parentId = null): void
    {
        $this->room = $room;
        $this->parentId = $parentId;
    }

    #[Computed]
    public function member(): ?Member
    {
        return Bonfire::memberFor(auth()->user());
    }

    public function send(): void
    {
        $body = trim($this->body);

        if ($body === '') {
            return;
        }

        $member = Bonfire::memberFor(auth()->user());
        abort_unless($member !== null && $member->is_active, 403);

        $parent = $this->parentId !== null ? Message::query()->findOrFail($this->parentId) : null;

        Bonfire::postAs($member, $this->room, $body, $parent);

        $this->body = '';
    }
};
