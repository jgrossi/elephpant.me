@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto px-4 pt-8">
        <div class="text-center mb-6">
            <flux:heading size="xl" level="1">Upload your elePHPoto!</flux:heading>
            <flux:text class="mt-2">Show the world your elePHPants.</flux:text>
        </div>
        <flux:card class="space-y-4">
            <div>
                <form action="{{ route('photos.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <flux:field>
                        <flux:label>Image URL</flux:label>
                        <flux:input type="url" name="url" id="url" placeholder="https://www.example.com/photo.jpg" />
                        <flux:description>Upload your photo to somewhere first.</flux:description>
                    </flux:field>
                    <flux:button type="submit" variant="primary">Save photo</flux:button>
                </form>
            </div>
        </flux:card>
    </div>
@endsection
