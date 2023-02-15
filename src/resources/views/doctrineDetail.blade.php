@extends('doctrine::base')

@section('title', 'Doctrine list')

@section('doctrineContent')
    <div class="card card-dark doctrine">
        <div class="card-header">
            <h3 class="card-title">{{ $doctrine['name'] }}</h3>
            @can("doctrine.create")
                <div class="card-tools pull-right">
                    <a
                        class="btn btn-xs btn-tool doctrine-edit"
                        data-placement="top"
                        href="{{ route('doctrine.doctrineEdit', ['id' => $doctrine['id']]) }}"
                        title="Edit"
                    >
                        <i class="fa fa-pen"></i>
                    </a>
                    <button
                        type="button"
                        class="btn btn-xs btn-tool doctrine-delete"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="New fitting"
                    >
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            @endcan
        </div>
        <div class="card-body">
            @if($doctrine['description'])
                <div class="alert alert-light" id="doctrine-description" role="alert">
                    {!! nl2br(e($doctrine['description'])) !!}
                </div>
            @endif
            @if(count($doctrine['fittings']) > 0)
                <table class="table fitting-table">
                    <thead>
                    <tr>
                        <th scope="col" class="fitting-table__image-col"></th>
                        <th scope="col" class="fitting-table__ship-col">Ship</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($doctrine['fittings'] as $fitting)
                        <tr>
                            <th scope="row" class="fitting-table__image-col">
                                <a
                                    class="link-dark"
                                    href="{{ route('doctrine.fittingDetail', ['id' => $fitting['id']]) }}"
                                >
                                    <img
                                        alt="{{ $fitting['ship'] }}"
                                        class="fitting-table__image"
                                        src="https://images.evetech.net/types/{{ $fitting['shipID'] }}/render?size=64"
                                    />
                                </a>
                            </th>
                            <th scope="row" class="fitting-table__ship-col">
                                <a
                                    class="text-dark"
                                    href="{{ route('doctrine.fittingDetail', ['id' => $fitting['id']]) }}"
                                >
                                    <u>{{ $fitting['ship'] }}</u>
                                </a>
                            </th>
                            <td>{{ $fitting['name'] }}</td>
                            <td>{{ $fitting['descriptionShort'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="d-flex justify-content-center flex-column align-items-center mt-5 mb-5">
                    <h6>So far there are no fittings in this doctrine</h6>
                    <div class="text-secondary">But soon there will be!</div>
                    @can("doctrine.create")
                        <a
                            href="{{ route('doctrine.doctrineEdit', ['id' => $doctrine['id']]) }}"
                            class="link-primary mt-2"
                        >
                            Edit doctrine
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="fitting-add-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Create new fitting</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form
                    role="form"
                    id="fitting-add-form"
                    action="{{ route('doctrine.fittingCreate') }}"
                    method="post"
                >
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="doctrine-add-name">EFT</label>
                            <textarea
                                name="eft"
                                rows="20"
                                style="width: 100%"
                            ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group pull-right" role="group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancel
                            </button>
                            <input
                                type="submit"
                                class="btn btn-success"
                                name="create"
                                value="Create"
                            />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@include(
    'doctrine::includes.markdownViewer',
    [
        'selector' => '#doctrine-description',
        'markdown' => $doctrine['description'],
    ]
)

@push('javascript')
    <script>
        $(document).ready(function() {
            $('.fitting-add').on('click', function() {
                $('#fitting-add-modal').modal('show');
                $('#fitting-add-modal [name="eft"]').val('').focus();
            });
        });
    </script>
@endpush
