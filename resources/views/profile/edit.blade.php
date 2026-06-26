@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 py-6 md:py-8 mb-6 md:mb-8">
            <div>
                <flux:heading size="xl" level="1">{{ __('Profile') }}</flux:heading>
                <flux:text class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Manage your account settings and public profile.</flux:text>
            </div>
        </div>

        @include('_status')

        <div class="space-y-12">
            {{-- Account --}}
            <div class="flex flex-col sm:flex-row sm:gap-12 gap-6">
                <div class="sm:w-48 shrink-0">
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">{{ __('Account') }}</flux:heading>
                    <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Your basic account information used across the site.</flux:text>
                </div>
                <div class="flex-1 min-w-0">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="account" />
                        <flux:field>
                            <flux:label>{{ __('Name') }}</flux:label>
                            <flux:input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus />
                            <flux:error name="name" />
                        </flux:field>
                        <flux:field>
                            <flux:label>{{ __('E-Mail Address') }}</flux:label>
                            <flux:input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required autocomplete="email" />
                            <flux:error name="email" />
                        </flux:field>
                        <flux:field>
                            <flux:label>{{ __('Country') }}</flux:label>
                            <flux:select name="country_code" id="country_code" required>
                                <option value="">-- {{ __('Select your country') }} --</option>
                                @foreach($countries as $code => $current)
                                    <option value="{{ $code }}" {{ $code === old('country_code', $user->country_code) ? 'selected' : '' }}>
                                        {{ is_array($current) ? ($current['name'] ?? '') : $current->get('name') }}
                                    </option>
                                @endforeach
                            </flux:select>
                            <flux:error name="country_code" />
                        </flux:field>
                        <flux:field>
                            <flux:label>{{ __('Username') }}</flux:label>
                            <flux:input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" autocomplete="username" />
                            <flux:error name="username" />
                        </flux:field>
                        <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
                    </form>
                </div>
            </div>

            <div class="border-t border-zinc-200 dark:border-zinc-700" aria-hidden="true"></div>

            {{-- Password --}}
            <div class="flex flex-col sm:flex-row sm:gap-12 gap-6">
                <div class="sm:w-48 shrink-0">
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">{{ __('Password') }}</flux:heading>
                    <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Leave blank to keep your current password.</flux:text>
                </div>
                <div class="flex-1 min-w-0">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="password" />
                        <flux:field>
                            <flux:label>{{ __('Password') }}</flux:label>
                            <flux:input type="password" name="password" id="password" autocomplete="new-password" />
                            <flux:error name="password" />
                        </flux:field>
                        <flux:field>
                            <flux:label>{{ __('Confirm Password') }}</flux:label>
                            <flux:input type="password" name="password_confirmation" id="password-confirm" autocomplete="new-password" />
                        </flux:field>
                        <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
                    </form>
                </div>
            </div>

            <div class="border-t border-zinc-200 dark:border-zinc-700" aria-hidden="true"></div>

            {{-- Public profile --}}
            <div class="flex flex-col sm:flex-row sm:gap-12 gap-6">
                <div class="sm:w-48 shrink-0">
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">{{ __('Public profile') }}</flux:heading>
                    <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Social links shown on your herd and in the trade area.</flux:text>
                </div>
                <div class="flex-1 min-w-0">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="public_profile" />
                        <flux:field>
                            <flux:label>{{ __('Twitter') }}</flux:label>
                            <flux:input type="text" name="twitter" id="twitter" value="{{ old('twitter', $user->twitter) }}" placeholder="@username" autocomplete="twitter" />
                            <flux:error name="twitter" />
                        </flux:field>
                        <flux:field>
                            <flux:label>{{ __('Mastodon') }}</flux:label>
                            <flux:input type="text" name="mastodon" id="mastodon" value="{{ old('mastodon', $user->mastodon) }}" placeholder="@username" />
                            <flux:error name="mastodon" />
                        </flux:field>
                        <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
