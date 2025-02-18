<flux:navbar class="max-2xl:hidden">
    @foreach ($items as $item)
        <x-flux-themes::navbar-item-loader :item="$item"/>
    @endforeach
</flux:navbar>
