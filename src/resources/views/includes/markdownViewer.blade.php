@push('head')
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css"
    >
@endpush

@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/marked@4.2.12/lib/marked.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.0/dist/purify.min.js"></script>
    <script>
        const el = document.querySelector('{{ $selector }}');
        const md = marked.parse({!! json_encode($markdown) !!});
        el.innerHTML = DOMPurify.sanitize(md);
        console.log(md);
    </script>
@endpush
