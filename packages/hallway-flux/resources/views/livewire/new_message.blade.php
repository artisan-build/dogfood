@php use ArtisanBuild\Hallway\Rules\UploadLimit; @endphp
<section name="new-message" x-data>
    <style>
        .filepond--credits {
            display: none
        }

        .filepond--drop-label {
            display: none !important
        }

        .filepond--panel-root {
            background-color: inherit !important;
        }

    </style>
    <form wire:submit.prevent="createMessage">


        <flux:textarea
            id="new-message"
            wire:model.defer="content"
            @keydown.enter.prevent="!$event.shiftKey && $wire.createMessage()"
            @keydown.shift.enter.stop="$event.target.value += '\n'"
            placeholder="Type your message... (Shift+Enter for newline)"
        ></flux:textarea>
        <div class="flex">
            <div class="flex-grow">
                <x-filepond::upload drop-on-page="true" drop-on-element="false" multiple="true" max-files="4"
                                    :accepted-file-types="UploadLimit::allowedMimeTypes()" wire:model="file"/>
            </div>
            <div class="flex-shrink-0 pr-2">
                <flux:button.group>
                    <flux:button icon="paper-clip" onclick="document.querySelector('[type=file]').click()"
                                 variant="ghost"></flux:button>
                </flux:button.group>


            </div>
            <div>
                <flux:button icon="paper-airplane" wire:click="createMessage" variant="primary"/>
            </div>
        </div>
    </form>



    <!-- TODO: This does not work -->
    <script>
        Livewire.on("filepond-reset-file", function () {
            window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });
        });
    </script>
</section>
