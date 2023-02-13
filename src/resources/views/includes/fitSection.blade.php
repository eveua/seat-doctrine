<div class="fit__section">
    <div class="fit__section-row fit__section-row--dark fit__section-row--bold">
        <img class="fit__image" src="{{ $logo }}" alt="{{ $name }}">
        <span>{{ $name }}</span>
    </div>
    @foreach ($modules as $module)
        <div class="fit__section-row">
            <img
                alt="{{ $module['name'] }}"
                class="fit__image"
                src="https://images.evetech.net/types/{{ $module['typeID'] }}/icon?size=32"
            />
            <span>{{ $module['name'] }}</span>
        </div>
    @endforeach
</div>
