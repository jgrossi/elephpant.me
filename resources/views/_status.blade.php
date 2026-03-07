@if(session('status-success'))
    <flux:callout variant="success" icon="check-circle" heading="{{ session('status-success') }}" class="mb-4" />
@endif

@if(session('status-warning'))
    <flux:callout variant="warning" icon="exclamation-triangle" heading="{{ session('status-warning') }}" class="mb-4" />
@endif

@if(session('status-info'))
    <flux:callout variant="info" icon="information-circle" heading="{{ session('status-info') }}" class="mb-4" />
@endif

@if(session('status-danger'))
    <flux:callout variant="danger" icon="x-circle" heading="{{ session('status-danger') }}" class="mb-4" />
@endif
