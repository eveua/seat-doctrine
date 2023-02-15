@extends('doctrine::base')

@section('title', 'Doctrine edit')

@section('doctrineContent')
    <div class="card card-dark doctrine">
        <div class="card-header">
            <h3 class="card-title">{{ $doctrine['name'] }}</h3>
            @can("doctrine.create")
                <div class="card-tools pull-right">
                    <button
                        type="button"
                        class="btn btn-xs btn-tool doctrine-delete"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Delete"
                    >
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            @endcan
        </div>
        <div class="card-body">
            <form
                class="doctrine__form"
                role="form"
                action="{{ route('doctrine.doctrineEdit', ['id' => $doctrine['id']]) }}"
                method="post"
            >
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="doctrine-form-name">Name</label>
                    <input
                        class="form-control"
                        type="text"
                        name="name" value="{{ $doctrine['name'] }}"
                        id="doctrine-form-name"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="doctrine-form-description">Description</label>
                    <textarea
                        class="form-control"
                        name="description"
                        rows="5"
                        id="doctrine-form-description"
                        style="width: 100%"
                    >{{ $doctrine['description'] }}</textarea>
                </div>

                <div class="form-group">
                    <label for="doctrine-form-fittings">Fittings</label>
                    <select
                        class="form-control"
                        id="doctrine-form-fittings"
                        name="fittings[]"
                        multiple="multiple"
                    ></select>
                </div>

                <div class="btn-group" role="group">
                    <a
                        class="btn btn-default"
                        href="{{ route('doctrine.doctrineDetail', ['id' => $doctrine['id']]) }}"
                        role="button"
                    >
                        Back to details
                    </a>
                    <input type="submit" class="btn btn-success" value="Update"/>
                </div>
            </form>
        </div>
    </div>

    @include(
        'doctrine::includes.doctrineDeleteModal',
        [
            'action' => route('doctrine.doctrineDelete', ['id' => $doctrine['id']]),
            'name' => $doctrine['name']
        ]
    )
@endsection

@include('doctrine::includes.markdownEditor', ['selector' => '#doctrine-form-description'])

@push('javascript')
    <script>
        $(document).ready(function () {
            $('.fitting-delete').on('click', function () {
                $('#fitting-delete-modal').modal('show');
            });

            const fittingsSelect = $('#doctrine-form-fittings');
            fittingsSelect.select2({
                closeOnSelect: false,
                ajax: {
                    url: '{{ route('doctrine.fittingList') }}?format=json',
                    dataType: 'json',
                    processResults: function (data) {
                        const results = data.map(function(item) {
                            return {
                                id: item.id,
                                text: `${item.name} (${item.ship})`,
                            };
                        })
                        return { results: results };
                    },
                },
            });
            @if(count($doctrine['fittings']) > 0)
                @foreach($doctrine['fittings'] as $fitting)
                    fittingsSelect.append(new Option(
                        "{{ $fitting['name'] }} ({{ $fitting['ship'] }})",
                        "{{ $fitting['id'] }}",
                        true,
                        true,
                    ));
                @endforeach
                fittingsSelect.trigger('change');
            @endif
        });
    </script>
@endpush
