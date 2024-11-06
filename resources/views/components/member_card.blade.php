@props(['member'])
@use('ArtisanBuild\Hallway\Members\Models\Member')
@php assert($member instanceof Member); @endphp

<div class="relative flex flex-col justify-between rounded-lg shadow-lg bg-gray-50 dark:bg-gray-600">
    <flux:badge :color="$member->role?->getColor()" class="absolute top-4 right-4 -mr-2 -mt-2">{{ $member->role?->name }}</flux:badge>
    <div class="p-4 flex gap-4 items-end">
        <img
            src="{{ $member->profile_picture_url }}"
            class="rounded-xl w-16 h-16 shadow-md"
        />
        <div class="flex flex-col">
            <span class="text-slate-400 dark:text-slate-400 text-sm">&commat;{{ $member->handle }}</span>
            {{-- <div>{{ $member->name }}</div> --}}
            <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ $member->display_name }}</span>
        </div>
    </div>
</div>
