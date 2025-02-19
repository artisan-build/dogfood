@php use ArtisanBuild\Hallway\Messages\Events\CommentCreated;use ArtisanBuild\Hallway\Messages\States\MessageState; @endphp
@props(['message'])
<div>
        <div class="hover:bg-zinc-50 dark:hover:bg-zinc-700 p-4">

            <x-hallway-flux::message :message="$message"/>
            <x-hallway-flux::reply :message="$message"/>
        </div>
</div>

