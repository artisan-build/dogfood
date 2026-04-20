<?php

declare(strict_types=1);

use ArtisanBuild\Bonfire\Facades\Bonfire;
use ArtisanBuild\Bonfire\Models\Attachment;
use ArtisanBuild\Bonfire\Models\Member;
use ArtisanBuild\Bonfire\Models\Message;
use ArtisanBuild\Bonfire\Models\Room;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

return new class extends Component
{
    use WithFileUploads;

    public Room $room;

    public ?int $parentId = null;

    public string $body = '';

    public ?string $scheduledFor = null;

    public bool $alsoSendToChannel = false;

    /** @var array<int, UploadedFile> */
    public array $pendingAttachments = [];

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

    /**
     * @return array<int, array{id: int, display_name: string, avatar_url: string}>
     */
    #[Computed]
    public function mentionables(): array
    {
        $tenantId = Bonfire::tenantId();

        return Member::query()
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('display_name')
            ->get(['id', 'display_name', 'avatar_url'])
            ->map(fn (Member $member): array => [
                'id' => $member->id,
                'display_name' => $member->display_name,
                'avatar_url' => $member->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->display_name),
            ])
            ->values()
            ->all();
    }

    public function removeAttachment(int $index): void
    {
        if (! array_key_exists($index, $this->pendingAttachments)) {
            return;
        }

        unset($this->pendingAttachments[$index]);
        $this->pendingAttachments = array_values($this->pendingAttachments);
    }

    public function send(): void
    {
        $body = trim($this->body);
        $plain = trim(strip_tags($body));

        if ($plain === '' && $this->pendingAttachments === []) {
            return;
        }

        $member = Bonfire::memberFor(auth()->user());
        abort_unless($member !== null && $member->is_active, 403);

        $parent = $this->parentId !== null ? Message::query()->findOrFail($this->parentId) : null;

        $effectiveBody = $plain === '' ? '📎' : $body;

        $scheduledAt = $this->scheduledFor !== null && $this->scheduledFor !== ''
            ? Carbon::parse($this->scheduledFor)
            : null;

        if ($scheduledAt !== null && $scheduledAt->isPast()) {
            $scheduledAt = null;
        }

        $message = $this->createMessage($member, $parent, $effectiveBody, $scheduledAt);

        $this->storeAttachments($message);

        if ($parent !== null && $this->alsoSendToChannel && $scheduledAt === null) {
            Bonfire::postAs($member, $this->room, $effectiveBody, null);
        }

        $this->reset('body', 'pendingAttachments', 'scheduledFor', 'alsoSendToChannel');
    }

    private function createMessage(Member $member, ?Message $parent, string $body, ?Carbon $scheduledAt): Message
    {
        if ($scheduledAt === null) {
            return Bonfire::postAs($member, $this->room, $body, $parent);
        }

        return Message::withoutEvents(fn (): Message => Message::query()->create([
            'tenant_id' => Bonfire::tenantId(),
            'room_id' => $this->room->getKey(),
            'member_id' => $member->getKey(),
            'parent_id' => $parent?->getKey(),
            'body' => $body,
            'scheduled_for' => $scheduledAt,
        ]));
    }

    private function storeAttachments(Message $message): void
    {
        if ($this->pendingAttachments === []) {
            return;
        }

        $disk = (string) config('bonfire.disk', 'public');

        foreach ($this->pendingAttachments as $upload) {
            $path = $upload->store('bonfire/attachments', $disk);

            if ($path === false) {
                continue;
            }

            Attachment::query()->create([
                'message_id' => $message->id,
                'disk' => $disk,
                'path' => $path,
                'filename' => $upload->getClientOriginalName(),
                'mime_type' => $upload->getMimeType() ?? 'application/octet-stream',
                'size' => $upload->getSize() ?? 0,
                'created_at' => now(),
            ]);
        }
    }
};
