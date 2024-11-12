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
            <span class="font-bold">{{ $message->member()->display_name }}</span>
            <span class="float-right text-sm italic">
                    {{ Snowflake::coerce($message->id)->toCarbon()->diffForHumans() }}
                </span>
        </div>


        <div x-data="{preview: @js($preview)}">
            <div x-show="preview === true">
                {!! trim($message->preview()) !!}@if ($message->needsPreview())&hellip; <div x-show="preview" x-on:click="preview = !preview"><flux:button variant="ghost" class="-ml-4">Read More</flux:button></div>@endif
            </div>
            <div x-cloak x-show="preview === false">
                {!! $message->rendered() !!}
            </div>



        </div>


    </div>
</div>





