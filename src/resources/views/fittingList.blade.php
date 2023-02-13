@extends('doctrine::base')

@section('title', 'Fitting list')

@section('doctrineContent')
    <div class="card card-dark fitting-list">
        <div class="card-header">
            <h3 class="card-title">Fittings</h3>
            @can("doctrine.create")
                <div class="card-tools pull-right">
                    <button
                        type="button"
                        class="btn btn-xs btn-tool fitting-add"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="New fitting"
                    >
                        <i class="fa fa-plus-square"></i>
                    </button>
                </div>
            @endcan
        </div>
        <div class="card-body">
            @if(count($fittings) > 0)
                <table class="table fitting-table" style="display: none">
                    <thead>
                        <tr>
                            <th scope="col" class="fitting-table__image-col"></th>
                            <th scope="col" class="fitting-table__ship-col">Ship</th>
                            <th scope="col">Name</th>
                            @can("doctrine.create")
                                <th scope="col" class="fitting-table__actions-col"></th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fittings as $fitting)
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
                                <th scope="row" class="align-middle fitting-table__ship-col">
                                    <a
                                        class="text-dark"
                                        href="{{ route('doctrine.fittingDetail', ['id' => $fitting['id']]) }}"
                                    >
                                        <u>{{ $fitting['ship'] }}</u>
                                    </a>
                                </th>
                                <td class="align-middle">
                                    {{ $fitting['name'] }}
                                </td>
                                @can("doctrine.create")
                                    <td class="fitting-table__actions-col">
                                        <a
                                            class="btn btn-warning text-white"
                                            data-placement="top"
                                            href="{{ route('doctrine.fittingEdit', ['id' => $fitting['id']]) }}"
                                            title="Edit"
                                        >
                                            <i class="fa fa-pen"></i>
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-danger fitting-delete"
                                            data-name="{{ $fitting['name'] }}"
                                            data-url="{{ route('doctrine.fittingDelete', ['id' => $fitting['id']]) }}"
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
                    <h6>Oops, no fittings yet.</h6>
                    <div class="text-secondary">Poke your FC to add some.</div>
                    @can("doctrine.create")
                        <button
                            type="button"
                            class="btn btn-success fitting-add mt-2"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            Create
                        </button>
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
                            <button
                                type="submit"
                                class="btn btn-primary"
                                name="next"
                                value="list"
                            >
                                Create
                            </button>
                            <button
                                type="submit"
                                class="btn btn-success"
                                name="next"
                                value="edit"
                            >
                                Create and open
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @can('doctrine.create')
        @include('doctrine::includes.fitDeleteModal', ['action' => '', 'name' => ''])
    @endcan
@endsection

@push('javascript')
    <script type="application/javascript">
        $(document).ready(function() {
            const $table = $('.fitting-table');
            $table.DataTable({
                columns: [
                    { orderable: false },
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

            $('.fitting-add').on('click', function() {
                $('#fitting-add-modal').modal('show');
                $('#fitting-add-modal [name="eft"]').val('').focus();
            });

            $('.fitting-delete').on('click', function(evt) {
                const data = evt.currentTarget.dataset;
                $('#fitting-delete-modal').modal('show');
                $('#fitting-delete-name').text(data.name);
                $('#fitting-delete-form').attr('action', data.url);
            });
        });
    </script>
@endpush
