<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Messages\States;

use ArtisanBuild\Hallway\Attachments\States\AttachmentState;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Moderation\Enums\ModerationMessageStates;
use ArtisanBuild\Hallway\TextRendering\Contracts\ConvertsMarkdownToHtml;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Thunk\Verbs\State;

class MessageState extends State
{
    public int $channel_id;
    public int $member_id;

    public ModerationMessageStates $moderation_state = ModerationMessageStates::None;
    public ?int $thread_id = null;

    public string $content;

    public ?Carbon $pinned_at = null;
    public ?int $pinned_by_id = null;

    public array $attachment_ids = [];

    public array $comments = [];
    public array $revisions = [];
    public array $mentions = [];

    public function member(): MemberState
    {
        return MemberState::load($this->member_id);
    }

    public function attachments()
    {
        return collect($this->attachment_ids)->map(fn($id) => AttachmentState::load($id));
    }

    public function pinned_by(): ?MemberState
    {
        return null === $this->pinned_by_id ? null : MemberState::load($this->pinned_by_id);
    }

    public function rendered(): string
    {
        return Blade::render(app(ConvertsMarkdownToHtml::class)($this->content)->parsed);
    }

    public function preview(): string
    {
        return Blade::render(app(ConvertsMarkdownToHtml::class)($this->content)->preview);
    }

    public function media(): array
    {
        return app(ConvertsMarkdownToHtml::class)($this->content)->media;
    }

    public function needsPreview(): bool
    {
        return strip_tags(trim($this->preview())) !== strip_tags(trim($this->rendered()));
    }

}
