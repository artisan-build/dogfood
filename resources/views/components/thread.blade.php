@php use ArtisanBuild\Hallway\Messages\Events\CommentCreated;use ArtisanBuild\Hallway\Messages\States\MessageState; @endphp
@props(['message'])
<div>
        <div class="hover:bg-gray-50 p-4">

            <x-hallway-flux::message :message="$message"/>
            <x-hallway-flux::reply :message="$message"/>
        </div>
</div>

