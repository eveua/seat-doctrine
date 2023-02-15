@extends('doctrine::base')

@section('title', 'Fitting detail')

@section('doctrineContent')
    <div class="card card-dark fitting">
        <div class="card-header">
            <h3 class="card-title">{{ $fitting['name'] }}</h3>
            @can("doctrine.create")
                <div class="card-tools pull-right">
                    <a
                        class="btn btn-xs btn-tool doctrine-add"
                        data-placement="top"
                        href="{{ route('doctrine.fittingEdit', ['id' => $fitting['id']]) }}"
                        title="Edit"
                    >
                        <i class="fa fa-pen"></i>
                    </a>
                    <button
                        type="button"
                        class="btn btn-xs btn-tool fitting-delete"
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
            <div class="fitting-ship">
                <img
                    alt="{{ $fitting['ship'] }}"
                    class="fitting-ship__image"
                    src="https://images.evetech.net/types/{{ $fitting['shipID'] }}/render?size=64"
                />
                <span>{{ $fitting['ship'] }}</span>
            </div>

            @if($fitting['description'])
                <div
                    class="alert alert-light fitting__description"
                    id="fitting-description"
                    role="alert"
                >
                    {!! nl2br(e($fitting['description'])) !!}
                </div>
            @endif

            @if(count($fitting['doctrines']) > 0)
                <div class="alert alert-light fitting__doctrines" role="alert">
                    <div>Used in doctrines:</div>
                </div>
            @endif

            <div class="fitting__fit">
                @include('doctrine::includes.fit', ['fitting' => $fitting])
            </div>
        </div>
    </div>

    @can('doctrine.create')
        @include(
            'doctrine::includes.fitDeleteModal',
            [
                'action' => route('doctrine.fittingDelete', ['id' => $fitting['id']]),
                'name' => $fitting['name']
            ]
        )
    @endcan
@endsection

@include(
    'doctrine::includes.markdownViewer',
    [
        'selector' => '#fitting-description',
        'markdown' => $fitting['description'],
    ]
)

@push('javascript')
    <script>
        $(document).ready(function() {
            $('#fitting-copy-eft').on('click', function() {
                $('#fitting-eft-modal').modal('show');
                const $eft = $('#fitting-eft-modal [name="eft"]');
                $eft.focus(function() { $(this).select(); });
                $eft.focus();
            });

            $('.fitting-delete').on('click', function() {
                $('#fitting-delete-modal').modal('show');
            });
        });
    </script>
@endpush
