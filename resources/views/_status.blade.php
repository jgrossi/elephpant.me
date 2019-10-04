@if(session('status-success'))
    <div class="alert alert-success mx-auto">
        {{ session('status-success') }}
    </div>
@endif

@if(session('status-warning'))
    <div class="alert alert-warning mx-auto">
        {{ session('status-warning') }}
    </div>
@endif

@if(session('status-info'))
    <div class="alert alert-info mx-auto">
        {{ session('status-info') }}
    </div>
@endif

@if(session('status-danger'))
    <div class="alert alert-danger mx-auto">
        {{ session('status-danger') }}
    </div>
@endif
