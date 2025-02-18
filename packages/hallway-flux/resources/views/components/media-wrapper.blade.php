@props(['attachment'])
    @php $name = implode('-', ['media', $attachment->id]); @endphp
    <div x-on:click="$dispatch('modal-show', { name: '{{ $name }}' })" class="w-1/2 space-x-2 mx-auto h-56 px-4 sm:px-6 lg:px-8 cursor-pointer">
        {!! $attachment->template->render($attachment->url) !!}
    </div>
    <flux:modal :name="implode('-', ['media', $attachment->id])">
        {!! $attachment->template->render($attachment->url) !!}
    </flux:modal>

