@php use ArtisanBuild\Hallway\Messages\Actions\ExtractMessagePreview;use Illuminate\Support\Carbon; @endphp
@php use Glhd\Bits\Snowflake; @endphp
@props(['message', 'preview' => true])

<div class="flex space-x-4 my-4">
    <div class="flex-shrink min-w-12">
        <img
            alt=""
            src="{{ $message->member()->profile_picture_url }}"
            class="rounded-xl w-10 h-10 shadow-md"
        />
    </div>
    <div class="flex-grow">
        <div>
            <flux:heading size="lg">
                {{ $message->member()->display_name }}
                <span class="float-right text-sm italic">
                    {{ Snowflake::coerce($message->id)->toCarbon()->diffForHumans() }}
                </span>
            </flux:heading>

        </div>


        <div x-data="{preview: @js($preview)}">
            <div class="space-y-6" x-show="preview === true">
                {!! trim($message->preview()) !!}@if ($message->needsPreview())&hellip; <div x-show="preview" x-on:click="preview = !preview"><flux:button variant="ghost" class="-ml-4 -mt-12">Read More</flux:button></div>@endif
            </div>
            <div class="space-y-6" x-cloak x-show="preview === false">
                {!! $message->rendered() !!}
            </div>
            @foreach ($message->media() as $media)
                {!! $media['linted'] !!}
                <div class="text-xs"><flux:link href="{{ $media['link'] }}" variant="subtle" external="true">{{ $media['link'] }}</flux:link></div>
            @endforeach
            @foreach ($message->attachments() as $attachment)
                {!! $attachment->template->render($attachment->url) !!}
            @endforeach
        </div>
    </div>
</div>





