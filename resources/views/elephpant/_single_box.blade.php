<div class="col-xl-3 col-md-4 col-sm-6 mb-3">
    <div class="card d-flex" id="elephpant-{{ $elephpant->id }}">
        <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" class="card-img-top" alt="{{ $elephpant->name }}">
        <div class="card-body">
            <h5 class="card-title">
                {{ $elephpant->name }}
            </h5>
            <p class="card-text">
                {{ $elephpant->description }}<br>
                <strong>{{ $elephpant->sponsor }}</strong><br>
                {{ $elephpant->year }}<br>
                @if ($elephpant->prototype)
                    <strong>Prototype Only</strong>
                @else
                    <strong>{{ $elephpant->users_count }} {{ Str::plural('owner', $elephpant->users_count) }}</strong>
                @endif
            </p>
        </div>
    </div>
</div>
