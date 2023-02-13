<div class="modal" tabindex="-1" role="dialog" id="doctrine-delete-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Delete doctrine</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form
                role="form"
                id="doctrine-delete-form"
                action="{{ $action }}"
                method="post"
            >
                {{ csrf_field() }}
                <div class="modal-body">
                    <p>
                        Are you sure you want to delete
                        <b id="doctrine-delete-name">{{ $name }}</b>
                        doctrine?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
