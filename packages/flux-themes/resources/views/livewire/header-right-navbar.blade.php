<flux:navbar>
    @foreach ($items as $item)
        <x-flux-themes::navbar-item-loader :item="$item"/>
    @endforeach
</flux:navbar>
