<div class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">{{ __('subscription.title') }}</h2>

    <form wire:submit="process" class="space-y-6">
        @if (session('error'))
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Lead Summary -->
        <div class="bg-base-200 p-4 rounded-lg mb-6">
            <h3 class="font-medium mb-2">{{ __('subscription.summary.title') }}</h3>
            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm text-base-content/70">{{ __('subscription.summary.price') }}</dt>
                    <dd class="font-medium">€{{ number_format($lead->calculated_price, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-base-content/70">{{ __('subscription.summary.copay') }}</dt>
                    <dd class="font-medium">{{ $lead->has_copay ? __('common.yes') : __('common.no') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Subscription Questions -->
        <div class="space-y-4">
            <div class="form-control">
                <label class="label cursor-pointer">
                    <span class="label-text">{{ __('subscription.questions.smoker') }}</span>
                    <input type="checkbox"
                           wire:model="is_smoker"
                           class="checkbox">
                </label>
                @error('is_smoker')
                <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('insurance.calculator') }}"
               class="btn btn-outline">
                {{ __('common.back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{ __('subscription.actions.continue') }}
            </button>
        </div>
    </form>
</div>
