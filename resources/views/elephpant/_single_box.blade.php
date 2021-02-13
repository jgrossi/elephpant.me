<div class="col-xl-3 col-md-4 col-sm-6 mb-4">
    <div class="card">
        <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" class="card-img-top" alt="{{ $elephpant->name }}">
        <div class="card-body">
            <h5 class="card-title">
                #{{ isset($key) ? count($elephpants) - $key : $elephpant->id }} -
                {{ $elephpant->name }}
            </h5>
            <p class="card-text">
                {{ $elephpant->description }}<br>
                <strong>{{ $elephpant->sponsor }}</strong><br>
                {{ $elephpant->year }}
            </p>
        </div>
    </div>
</div>
