@php use Illuminate\Support\Carbon; @endphp
@php use Glhd\Bits\Snowflake; @endphp
@props(['message'])
<div class="flex space-x-4">
    <div class="flex-shrink">
        <img
            alt=""
            src="{{ $message->member()->profile_picture_url }}"
            class="rounded-xl w-10 h-10 shadow-md"
        />
    </div>
    <div class="flex-grow">
        <div>
            {{ $message->member()->display_name }}
                <span class="float-right text-sm italic">
                    {{ Snowflake::coerce($message->id)->toCarbon()->diffForHumans() }}
                </span>
        </div>

        <x-markdown :content="$message->content"/>
    </div>
</div>





