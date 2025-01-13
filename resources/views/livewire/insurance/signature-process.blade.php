{{-- resources/views/livewire/insurance/signature-process.blade.php --}}
<div class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">{{ __('signature.title') }}</h2>

    <form wire:submit="sign" class="space-y-8">
        @if (session('error'))
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Document Preview -->
        <div class="bg-base-200 p-6 rounded-lg">
            <h3 class="text-lg font-medium mb-4">{{ __('signature.document.preview') }}</h3>
            <div class="bg-white p-6 rounded border whitespace-pre-wrap font-mono text-sm">
                {{ $dummy_document }}
            </div>
        </div>

        <!-- Signature Acceptance -->
        <div class="space-y-4">
            <div class="form-control">
                <label class="label cursor-pointer">
                    <span class="label-text">
                        {{ __('signature.acceptance.text') }}
                    </span>
                    <input type="checkbox"
                           wire:model="accepted"
                           class="checkbox">
                </label>
                @error('accepted')
                <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('payment.process', ['lead' => $lead]) }}"
               class="btn btn-outline">
                {{ __('common.back') }}
            </a>
            <button type="submit"
                    class="btn btn-primary"
                    wire:loading.attr="disabled"
                    wire:loading.class="loading">
                {{ __('signature.actions.sign') }}
            </button>
        </div>
    </form>
</div>
