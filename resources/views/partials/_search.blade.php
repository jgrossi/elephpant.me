<div class="row align-items-center mb-4">
    <div class="col-12 col-md">
        <form action="">
            <input type="text" class="form-control w-50 mr-1 float-left" name="q" placeholder="Search for Elephpants" value="{{ request()->get('q') }}">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
    <div class="col-12 col-md-auto">
        <strong>Species Found:</strong> {{ count($elephpants) }}
    </div>
</div>
