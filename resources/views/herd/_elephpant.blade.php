<li data-controller="counter"
    data-counter-id="{{ $elephpant->id }}"
    class="list-group-item {{ $key % 2 === 0 ? 'bg-white' : 'bg-light' }} {{ isset($userElephpants[$elephpant->id]) ? 'has-elephpants' : '' }}">
    <div class="row align-items-center no-gutters">
        <div class="col-xl-9 col-lg-8">
            <div class="float-left mr-3">
                <div class="media">
                    <a href="javascript:void(0)" data-toggle="popover" data-content="<img src='{{ asset('elephpants/' . $elephpant->image) }}' width='150'/>" data-placement="left" data-trigger="hover">
                        <img src="{{ asset('elephpants/' . $elephpant->image) }}" width="50" alt="" class="img-thumbnail rounded img-fluid mr-3">
                    </a>
                    <div class="media-body">
                        <p class="mb-0"><strong>{{ $elephpant->name }}</strong> - {{ $elephpant->description }}</p>
                        <p class="mb-0 text-black-50">
                            Sponsor: {{ $elephpant->sponsor }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3">
            <div class="float-right">
                <div class="input-group input-group-lg mb-0">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary" type="button" data-action="counter#decrement">-</button>
                    </div>
                    <input type="text" class="form-control text-center" placeholder="0" value="{{ $userElephpants[$elephpant->id] ?? 0 }}" data-target="counter.quantity">
                    <div class="input-group-append" id="button-addon4">
                        <button class="btn btn-outline-secondary" type="button" data-action="counter#increment">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
