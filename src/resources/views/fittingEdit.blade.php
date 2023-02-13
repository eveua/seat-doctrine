@extends('doctrine::base')

@section('title', 'Fitting edit')

@section('doctrineContent')
    <div class="card card-dark fitting">
        <div class="card-header">
            <h3 class="card-title">{{ $fitting['name'] }}</h3>
            @can("doctrine.create")
                <div class="card-tools pull-right">
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

            <form
                class="fitting__form"
                role="form"
                action="{{ route('doctrine.fittingEdit', ['id' => $fitting['id']]) }}"
                method="post"
            >
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="fitting-form-name">Name</label>
                    <input
                        class="form-control"
                        type="text"
                        name="name" value="{{ $fitting['name'] }}"
                        id="fitting-form-name"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="doctrine-add-name">Description</label>
                    <textarea
                        class="form-control"
                        name="description"
                        rows="5"
                        style="width: 100%"
                    >{{ $fitting['description'] }}</textarea>
                </div>

                <div class="btn-group" role="group">
                    <a
                        class="btn btn-default"
                        href="{{ route('doctrine.fittingDetail', ['id' => $fitting['id']]) }}"
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
        'doctrine::includes.fitDeleteModal',
        [
            'action' => route('doctrine.fittingDelete', ['id' => $fitting['id']]),
            'name' => $fitting['name']
        ]
    )
@endsection

@push('javascript')
    <script>
        $(document).ready(function () {
            $('.fitting-delete').on('click', function () {
                $('#fitting-delete-modal').modal('show');
            });
        });
    </script>
@endpush
