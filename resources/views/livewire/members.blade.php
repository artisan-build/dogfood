@use('ArtisanBuild\Hallway\Members\Models\Member')
<div>
    @if ($channel)
        <h3 class="text-xl font-semibold dark:text-gray-100">'{{$channel->name}}' Channel Members</h3>

        @forelse($channel->member_ids as $member_id)
            <x-hallway-flux::member_card :member="Member::find($member_id)"/>
        @empty
            <p class="text-gray-400 dark:text-gray-500">No members in this channel yet.</p>
        @endforelse
    @else
        <h3 class="text-xl font-semibold dark:text-gray-100">Community Members</h3>

        @php $members = Member::paginate(12);  @endphp
        <div class="mt-8 gap-4 grid grid-cols-1 md:grid-cols-3">
            @foreach ($members as $member)
                <x-hallway-flux::member_card :member="$member"/>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $members->links() }}
        </div>
    @endif
</div>
