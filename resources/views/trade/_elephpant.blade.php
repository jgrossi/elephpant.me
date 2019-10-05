<li class="list-group-item align-items-center">
    <div class="float-right">
        <span class="badge badge-pill badge-secondary">{{ $elephpant->pivot->quantity - 1 }}</span>
    </div>
    <div>
        <div class="float-left mr-3">
            <div class="media">
                <a href="javascript:void(0)" data-toggle="popover" data-content="<img src='{{ asset('storage/elephpants/' . $elephpant->image) }}' width='150'/>" data-placement="left" data-trigger="hover">
                    <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" width="50" alt="" class="img-thumbnail rounded img-fluid mr-3">
                </a>
                <div class="media-body">
                    <p class="mb-0"><strong>{{ $elephpant->name }}</strong> <em>({{ $elephpant->description }})</em></p>
                    <p class="mb-0">By {{ $elephpant->sponsor }}</p>
                </div>
            </div>
        </div>
    </div>
</li>
