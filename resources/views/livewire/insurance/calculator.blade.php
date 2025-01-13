<div class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">{{ __('insurance.calculator.title') }}</h2>

    <form wire:submit="calculate" class="space-y-6">
        @if ($error_message)
            <div class="alert alert-error">
                <span>{{ $error_message }}</span>
            </div>
        @endif

        <!-- Teléfono -->
        <div>
            <label class="label">
                <span class="label-text">{{ __('insurance.calculator.phone') }}</span>
            </label>
            <input type="tel"
                   wire:model="phone"
                   class="input input-bordered w-full"
                   placeholder="{{ __('insurance.calculator.phone') }}">
            @error('phone')
            <span class="text-error text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Fecha de Nacimiento Principal -->
        <div>
            <label class="label">
                <span class="label-text">{{ __('insurance.calculator.birth_date') }}</span>
            </label>
            <input type="date"
                   wire:model="main_insured_birth_date"
                   class="input input-bordered w-full">
            @error('main_insured_birth_date')
            <span class="text-error text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Copago -->
        <div class="form-control">
            <label class="label cursor-pointer">
                <span class="label-text">{{ __('insurance.calculator.copay') }}</span>
                <input type="checkbox"
                       wire:model="has_copay"
                       class="checkbox">
            </label>
        </div>


        <!-- Asegurados Adicionales -->
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium">Asegurados Adicionales</h3>
                <button type="button"
                        wire:click="addInsured"
                        class="btn btn-secondary btn-sm"
                        @if(count($additional_insured) >= 2) disabled @endif>
                    Añadir Asegurado
                </button>
            </div>

            @foreach($additional_insured as $index => $insured)
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <label class="label">
                            <span class="label-text">Fecha de Nacimiento</span>
                        </label>
                        <input type="date"
                               wire:model="additional_insured.{{ $index }}.birth_date"
                               class="input input-bordered w-full">
                        @error("additional_insured.{$index}.birth_date")
                        <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="button"
                            wire:click="removeInsured({{ $index }})"
                            class="btn btn-error btn-sm mt-8">
                        Eliminar
                    </button>
                </div>
            @endforeach
        </div>


        <!-- Calculation Results -->
        @if($calculation_result)
            <div class="bg-base-200 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-2">{{ __('insurance.summary.title') }}</h3>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt>{{ __('insurance.summary.base_price') }}:</dt>
                        <dd>€{{ number_format($calculation_result['base_price'], 2) }}</dd>
                    </div>
                    @if($calculation_result['discount_percentage'] > 0)
                        <div class="flex justify-between text-success">
                            <dt>{{ __('insurance.summary.discount', ['percentage' => $calculation_result['discount_percentage']]) }}
                                :
                            </dt>
                            <dd>-€{{ number_format($calculation_result['discount_amount'], 2) }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold">
                        <dt>{{ __('insurance.summary.final_price') }}:</dt>
                        <dd>€{{ number_format($calculation_result['final_price'], 2) }}</dd>
                    </div>
                </dl>
            </div>
        @endif


        <button type="submit" class="text-white px-4 py-2 bg-blue-600 w-full">
            {{ __('insurance.calculator.calculate') }}
        </button>
    </form>
</div>
