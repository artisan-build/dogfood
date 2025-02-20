@php use ArtisanBuild\Hallway\Messages\Actions\ExtractMessagePreview;use Illuminate\Support\Carbon; @endphp
@php use Glhd\Bits\Snowflake; @endphp
@props(['message', 'preview' => true])

<div class="flex space-x-4 my-4">
    <div class="shrink min-w-12">
        <img
            alt=""
            src="{{ $message->member()->profile_picture_url }}"
            class="rounded-xl w-10 h-10 shadow-md"
        />
    </div>
    <div class="grow">
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

            <div x-data>
                @if ($message->attachments()->count() > 1)
                    <flux:tab.group>
                        @foreach ($message->attachments() as $attachment)
                            <flux:tab.panel :name="implode('-', ['at', $message->id, $loop->iteration])">
                                <x-hallway-flux::media-wrapper :attachment="$attachment"/>
                            </flux:tab.panel>
                        @endforeach
                        <div class="text-center"><flux:tabs variant="segmented" size="sm">
                                @foreach (range(1, $message->attachments()->count()) as $number)
                                    <flux:tab :name="implode('-', ['at', $message->id, $loop->iteration])">{{ $number }}</flux:tab>
                                @endforeach
                            </flux:tabs></div>
                    </flux:tab.group>

                @elseif ($message->attachments()->count() === 1)
                    <x-hallway-flux::media-wrapper :attachment="$message->attachments()->first()"/>
                @endif
            </div>

        </div>
    </div>
</div>





