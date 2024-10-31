@use('ArtisanBuild\Hallway\Members\Models\Member')
<div>
    @if ($channel)
        <h3 class="text-xl font-semibold dark:text-gray-100">Members in {{$channel->name}}</h3>

    @else
        <h3 class="text-xl font-semibold dark:text-gray-100">Community Members</h3>

    @endif
        @php $members = Member::paginate(12);  @endphp
        <div class="mt-8 gap-4 grid grid-cols-1 md:grid-cols-3">
            @foreach ($members as $member)
                <x-hallway-flux::member_card :member="$member"/>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $members->links() }}
        </div>
</div>
