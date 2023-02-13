<div class="fit">
    <div class="fit__controls">
        <button id="fitting-copy-eft" type="button" class="btn btn-success">
            Copy fit (EFT)
        </button>
    </div>
    <div class="fit__content">
        @include(
            'doctrine::includes.fitSection',
            [
                'name' => 'High',
                'modules' => $fitting['fit']['slotHigh'],
                'showCount' => false,
                'logo' => asset('web/doctrine/img/slot_high.png'),
            ]
        )
        @include(
            'doctrine::includes.fitSection',
            [
                'name' => 'Medium',
                'modules' => $fitting['fit']['slotMed'],
                'showCount' => false,
                'logo' => asset('web/doctrine/img/slot_med.png'),
            ]
        )
        @include(
            'doctrine::includes.fitSection',
            [
                'name' => 'Low',
                'modules' => $fitting['fit']['slotLow'],
                'showCount' => false,
                'logo' => asset('web/doctrine/img/slot_low.png'),
            ]
        )
        @include(
            'doctrine::includes.fitSection',
            [
                'name' => 'Rig',
                'modules' => $fitting['fit']['slotRig'],
                'showCount' => false,
                'logo' => asset('web/doctrine/img/slot_rig.png'),
            ]
        )
        @if(count($fitting['fit']['slotSub']) > 0)
            @include(
                'doctrine::includes.fitSection',
                [
                    'name' => 'Subsystem',
                    'modules' => $fitting['fit']['slotSub'],
                    'showCount' => false,
                    'logo' => asset('web/doctrine/img/slot_rig.png'),
                ]
            )
        @endif
        @if(count($fitting['fit']['drones']) > 0)
            @include(
                'doctrine::includes.fitSection',
                [
                    'name' => 'Drones',
                    'modules' => $fitting['fit']['drones'],
                    'showCount' => true,
                    'logo' => asset('web/doctrine/img/drones.png'),
                ]
        )
        @endif
        @if(count($fitting['fit']['cargo']) > 0)
            @include(
                'doctrine::includes.fitSection',
                [
                    'name' => 'Cargo',
                    'modules' => $fitting['fit']['cargo'],
                    'showCount' => true,
                    'logo' => asset('web/doctrine/img/cargo.png'),
                ]
            )
        @endif
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="fitting-eft-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">{{ $fitting['ship'] }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="doctrine-add-name">EFT</label>
                    <textarea
                            name="eft"
                            rows="20"
                            style="width: 100%"
                    >{{ $fitting['fit']['eft'] }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group pull-right" role="group">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('javascript')
    <script>
        $(document).ready(function() {
            $('#fitting-copy-eft').on('click', function() {
                $('#fitting-eft-modal').modal('show');
                const $eft = $('#fitting-eft-modal [name="eft"]');
                $eft.focus(function() { $(this).select(); });
                $eft.focus();
            });
        });
    </script>
@endpush
