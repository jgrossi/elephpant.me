@if(session('status-success'))
    <div class="w-50 alert alert-success mx-auto">
        {{ session('status-success') }}
    </div>
@endif

@if(session('status-warning'))
    <div class="w-50 alert alert-warning mx-auto">
        {{ session('status-warning') }}
    </div>
@endif

@if(session('status-info'))
    <div class="w-50 alert alert-info mx-auto">
        {{ session('status-info') }}
    </div>
@endif

@if(session('status-danger'))
    <div class="w-50 alert alert-danger mx-auto">
        {{ session('status-danger') }}
    </div>
@endif
