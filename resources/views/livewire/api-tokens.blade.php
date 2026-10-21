<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 py-6 md:py-8 mb-6 md:mb-8">
        <div>
            <flux:heading size="xl" level="1">{{ __('API tokens') }}</flux:heading>
            <flux:text class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Tokens let you automate things or use the <flux:link href="{{ url('/docs') }}">API</flux:link> from an AI agent. Send one as <code>Authorization: Bearer {token}</code>.
            </flux:text>
        </div>
        <flux:modal.trigger name="create-token">
            <flux:button variant="primary" icon="plus">{{ __('Create token') }}</flux:button>
        </flux:modal.trigger>
    </div>

    @if($plainTextToken)
        <flux:callout variant="success" icon="key">
            <flux:callout.heading>{{ __('Your new token') }}</flux:callout.heading>
            <flux:callout.text>Copy it now: it is shown only once.</flux:callout.text>
            <div class="mt-2">
                <flux:input :value="$plainTextToken" readonly copyable aria-label="{{ __('Your new token') }}" />
            </div>
        </flux:callout>
    @endif

    <flux:card>
        @if($tokens->isEmpty())
            <flux:text>You don't have any API tokens yet.</flux:text>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Created</flux:table.column>
                    <flux:table.column>Last used</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($tokens as $token)
                        <flux:table.row :key="$token->id">
                            <flux:table.cell>{{ $token->name }}</flux:table.cell>
                            <flux:table.cell>{{ $token->created_at->diffForHumans() }}</flux:table.cell>
                            <flux:table.cell>{{ $token->last_used_at?->diffForHumans() ?? 'Never' }}</flux:table.cell>
                            <flux:table.cell align="end">
                                <flux:button size="sm" variant="danger" wire:click="revoke({{ $token->id }})" wire:confirm="Revoke the token {{ $token->name }}? Anything using it stops working.">
                                    {{ __('Revoke') }}
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif
    </flux:card>

    <flux:modal name="create-token" class="md:w-96">
        <form wire:submit="create" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Create token') }}</flux:heading>
                <flux:text class="mt-2">Give it a name, so you can tell your tokens apart.</flux:text>
            </div>
            <flux:field>
                <flux:label>{{ __('Name') }}</flux:label>
                <flux:input wire:model="name" placeholder="my-agent" required maxlength="255" />
                <flux:error name="name" />
            </flux:field>
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">{{ __('Create') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
