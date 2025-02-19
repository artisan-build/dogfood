@use('ArtisanBuild\Hallway\Support\Functions')
@props(['event'])
@if (Functions::can($event, \Illuminate\Support\Facades\Context::get('active_member')))
    {{ $slot }}
@endif
