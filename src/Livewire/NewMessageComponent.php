<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Attachments\Events\FileAttachedToMessage;
use ArtisanBuild\Hallway\Messages\Events\CommentCreated;
use ArtisanBuild\Hallway\Messages\Events\MessageCreated;
use ArtisanBuild\Hallway\Rules\UploadLimit;
use Flux\Flux;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;

class NewMessageComponent extends Component
{
    use WithFilePond;
    public $file;
    public $attachments;

    public string $content = '';
    public ?int $channel_id = null;
    public ?int $thread_id = null;
    public string $disk = 'public';

    public function mount(): void
    {
        $this->channel_id = Context::get('channel')?->id;
        $this->thread_id = Context::get('thread')?->id;
        $this->attachments = collect();

    }

    public function rules(): array
    {
        return [
            'file' => [
                'nullable',
                'file',
                'mimetypes:' . implode(',', UploadLimit::allowedMimeTypes()),
                new UploadLimit(),
            ],
        ];
    }
    public function validateUploadedFile(): true
    {
        $this->validate();

        $this->attachments->push($this->file);

        return true;
    }

    public function createDraft(): void {}

    public function createMessage(): void
    {
        // We don't want to throw an exception here. Just ignore the button click and wait for some real content.
        if ('' === $this->content) {
            return;
        }

        $message_id = snowflake_id();

        if ($this->thread_id === null) {
            MessageCreated::commit(
                message_id: $message_id,
                channel_id: $this->channel_id,
                content: $this->content,
            );
        }
        else {
            CommentCreated::commit(
                message_id: $message_id,
                channel_id: $this->channel_id,
                thread_id: $this->thread_id,
                content: $this->content,
            );
        }


        $this->attachments->each(function (TemporaryUploadedFile $attachment) use ($message_id): void {
            $attachment_id = snowflake_id();
            $attachment->storePubliclyAs(path: (string) $message_id, name: implode('.', [$attachment_id, $attachment->extension()]), options: ['disk' => $this->disk]);
            FileAttachedToMessage::fire(
                attachment_id: $attachment_id,
                message_id: $message_id,
                member_id: Context::get('active_member')->id,
                url: Storage::disk($this->disk)->url(implode('/', [$message_id, implode('.', [$attachment_id, $attachment->extension()])])),
            );
        });

        $this->reset(['content']);
        $this->dispatch('saved');

        Flux::toast('Your message has been posted', 'Saved!', 3, 'success');
        $this->file = null;
        $this->attachments = collect();
        $this->dispatch('filepond-reset-file');
    }

    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View|null
    {
        return view('hallway-flux::livewire.new_message');
    }
}
