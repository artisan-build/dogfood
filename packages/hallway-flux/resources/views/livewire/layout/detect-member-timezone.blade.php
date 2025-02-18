<div>
    @script
    <script>
        $wire.call('setTimezone', Intl.DateTimeFormat().resolvedOptions().timeZone);
    </script>
    @endscript
</div>
