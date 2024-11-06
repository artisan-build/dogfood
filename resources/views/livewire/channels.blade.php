<div class="flex">
    @foreach ($channels as $channel)
        <flux:card>
            <flux:heading size="lg">Are you sure?</flux:heading>

            <flux:subheading class="mb-4">
                <p>Your post will be deleted permanently.</p>
                <p>This action cannot be undone.</p>
            </flux:subheading>

            <flux:button variant="danger">Delete</flux:button>
        </flux:card>
    @endforeach
</div>
