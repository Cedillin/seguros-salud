{{-- resources/views/livewire/insurance/payment-process.blade.php --}}
<div class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">{{ __('payment.title') }}</h2>

    <form wire:submit="process" class="space-y-8">
        @if (session('error'))
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Price Summary -->
        <div class="bg-base-200 p-6 rounded-lg">
            <h3 class="text-lg font-medium mb-4">{{ __('payment.summary.title') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Monthly Option -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <label class="label cursor-pointer">
                            <div>
                                <span class="card-title text-lg">{{ __('payment.summary.monthly') }}</span>
                                <p class="text-2xl font-bold">€{{ number_format($monthly_price, 2) }}/{{ __('payment.summary.month') }}</p>
                            </div>
                            <input type="radio"
                                   wire:model="payment_frequency"
                                   value="monthly"
                                   class="radio radio-primary" />
                        </label>
                    </div>
                </div>

                <!-- Annual Option -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <label class="label cursor-pointer">
                            <div>
                                <span class="card-title text-lg">{{ __('payment.summary.annual') }}</span>
                                <p class="text-2xl font-bold">€{{ number_format($annual_price, 2) }}/{{ __('payment.summary.year') }}</p>
                                <span class="badge badge-success">{{ __('payment.summary.save') }} 5%</span>
                            </div>
                            <input type="radio"
                                   wire:model="payment_frequency"
                                   value="annual"
                                   class="radio radio-primary" />
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Method Selection -->
        <div class="space-y-4">
            <h3 class="text-lg font-medium">{{ __('payment.method.title') }}</h3>

            <div class="tabs tabs-boxed">
                <a class="tab {{ $payment_method === 'card' ? 'tab-active' : '' }}"
                   wire:click="$set('payment_method', 'card')">
                    {{ __('payment.method.card') }}
                </a>
                <a class="tab {{ $payment_method === 'sepa' ? 'tab-active' : '' }}"
                   wire:click="$set('payment_method', 'sepa')">
                    {{ __('payment.method.sepa') }}
                </a>
            </div>

            <!-- Card Payment Form -->
            <div x-show="$wire.payment_method === 'card'" class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">{{ __('payment.card.number') }}</span>
                    </label>
                    <input type="text"
                           wire:model="card_number"
                           class="input input-bordered"
                           placeholder="0000 0000 0000 0000"
                           x-mask="9999 9999 9999 9999">
                    @error('card_number')
                    <span class="text-error text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">{{ __('payment.card.expiry') }}</span>
                        </label>
                        <input type="text"
                               wire:model="card_expiry"
                               class="input input-bordered"
                               placeholder="MM/YY"
                               x-mask="99/99">
                        @error('card_expiry')
                        <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">{{ __('payment.card.cvv') }}</span>
                        </label>
                        <input type="text"
                               wire:model="card_cvv"
                               class="input input-bordered"
                               placeholder="000"
                               x-mask="999">
                        @error('card_cvv')
                        <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SEPA Payment Form -->
            <div x-show="$wire.payment_method === 'sepa'" class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">{{ __('payment.sepa.iban') }}</span>
                    </label>
                    <input type="text"
                           wire:model="iban"
                           class="input input-bordered uppercase"
                           placeholder="ES91 2100 0418 4502 0005 1332"
                           x-mask="aa99 9999 9999 9999 9999 99">
                    @error('iban')
                    <span class="text-error text-sm">{{ $message }}</span>
                    @enderror
                    <label class="label">
                        <span class="label-text-alt">{{ __('payment.sepa.example') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('subscription.motor', ['lead' => $lead]) }}"
               class="btn btn-outline">
                {{ __('common.back') }}
            </a>
            <button type="submit"
                    class="btn btn-primary"
                    wire:loading.attr="disabled"
                    wire:loading.class="loading">
                {{ __('payment.actions.process') }}
            </button>
        </div>
    </form>
</div>
