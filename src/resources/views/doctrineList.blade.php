@extends('doctrine::base')

@section('title', 'Doctrine list')

@section('doctrineContent')
    <div class="card card-dark doctrine-list">
        <div class="card-header">
            <h3 class="card-title">Doctrines</h3>
            @can("doctrine.create")
                <div class="card-tools pull-right">
                    <button
                        type="button"
                        class="btn btn-xs btn-tool doctrine-add"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="New doctrine"
                    >
                        <i class="fa fa-plus-square"></i>
                    </button>
                </div>
            @endcan
        </div>
        <div class="card-body">
            @if(count($doctrines) > 0)
                <table class="table doctrine-table" style="display: none">
                    <thead>
                        <tr>
                            <th scope="col" class="doctrine-table__name-col">Name</th>
                            <th scope="col">Ships</th>
                            @can("doctrine.create")
                                <th scope="col" class="doctrine-table__actions-col"></th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($doctrines as $doctrine)
                            <tr>
                                <th scope="row" class="doctrine-table__name-col">
                                    <a
                                        class="text-dark"
                                        href="{{ route('doctrine.doctrineDetail', ['id' => $doctrine['id']]) }}"
                                    >
                                        <u>{{ $doctrine['name'] }}</u>
                                    </a>
                                </th>
                                <td>
                                    @if(count($doctrine['fittings']) > 0)
                                        @foreach($doctrine['fittings'] as $fitting)
                                            <img
                                                alt="{{ $fitting['ship'] }}"
                                                class="doctrine-table__image"
                                                src="https://images.evetech.net/types/{{ $fitting['shipID'] }}/render?size=64"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="{{ $fitting['ship'] }}"
                                            />
                                        @endforeach
                                    @else
                                        <span class="text-muted">
                                            <em>- No ships -</em>
                                        </span>
                                    @endif
                                </td>
                                @can("doctrine.create")
                                    <td class="doctrine-table__actions-col">
                                        <a
                                            class="btn btn-warning text-white"
                                            data-placement="top"
                                            href="{{ route('doctrine.doctrineEdit', ['id' => $doctrine['id']]) }}"
                                            title="Edit"
                                        >
                                            <i class="fa fa-pen"></i>
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-danger doctrine-delete"
                                            data-name="{{ $doctrine['name'] }}"
                                            data-url="{{ route('doctrine.doctrineDelete', ['id' => $doctrine['id']]) }}"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="d-flex justify-content-center flex-column align-items-center">
                    <h6>No doctrines found</h6>
                    <div class="text-secondary">Poke your FC to add some.</div>
                    <button
                        type="button"
                        class="btn btn-success doctrine-add mt-2"
                        data-toggle="tooltip"
                        data-placement="top"
                    >
                        Create
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="doctrine-add-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 id="fitting-edit-title" class="modal-title">Create new doctrine</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form
                    role="form"
                    id="doctrine-add-form"
                    action="{{ route('doctrine.doctrineCreate') }}"
                    method="post"
                >
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="doctrine-add-name">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                name="name" id="doctrine-add-name"
                            >
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

    @can('doctrine.create')
        @include('doctrine::includes.doctrineDeleteModal', ['name' => '', 'action' => ''])
    @endcan
@endsection

@push('javascript')
    <script type="application/javascript">
        $(document).ready(function() {
            const $table = $('.doctrine-table');
            $table.DataTable({
                columns: [
                    { orderable: false },
                    { orderable: false },
                    @can("doctrine.create")
                        { orderable: false },
                    @endcan
                ],
                paging: false,
            });
            $table.find('th').removeClass('sorting_desc');
            $table.show();

            $('.doctrine-add').on('click', function() {
                $('#doctrine-add-modal').modal('show');
                $('#doctrine-add-modal [name="name"]').val('').focus();
            });

            $('.doctrine-delete').on('click', function(evt) {
                const data = evt.currentTarget.dataset;
                $('#doctrine-delete-modal').modal('show');
                $('#doctrine-delete-name').text(data.name);
                $('#doctrine-delete-form').attr('action', data.url);
            });
        });
    </script>
@endpush
