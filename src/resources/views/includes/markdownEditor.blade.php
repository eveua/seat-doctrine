@push('head')
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css"
    >
@endpush

@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
    <script>
        $(document).ready(function() {
            new SimpleMDE({
                autofocus: true,
                element: document.querySelector('{{ $selector }}'),
                hideIcons: ['side-by-side'],
                status: false,
            });
        })
    </script>
@endpush
