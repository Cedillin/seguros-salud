<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-400 mb-4 md:mb-0">{{ __('client.portal.title') }}</h2>
        <a href="{{ route('insurance.calculator') }}"
           class="btn btn-primary btn-wide">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                      clip-rule="evenodd"/>
            </svg>
            {{ __('client.portal.new_policy') }}
        </a>
    </div>

    <!-- Policies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($policies as $policy)
            <div
                class="card bg-white shadow-lg rounded-xl overflow-hidden transform transition-all hover:scale-105 hover:shadow-2xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">
                            {{ __('client.portal.policy.summary') }}
                        </h3>
                        <span
                            class="badge badge-{{ $policy->status === 'active' ? 'success' : 'warning' }} badge-outline">
                            {{ ucfirst($policy->status) }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-xs text-gray-500">{{ __('client.portal.policy.number') }}</p>
                                <p class="font-medium text-gray-700">{{ $policy->policy_number }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">{{ __('client.portal.policy.premium') }}</p>
                                <p class="font-bold text-primary">
                                    €{{ number_format($policy->premium_amount, 2) }}/
                                    {{ $policy->payment_frequency === 'monthly' ? __('common.month') : __('common.year') }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-xs text-gray-500">{{ __('client.portal.policy.start_date') }}</p>
                                <p class="text-sm text-gray-700">{{ $policy->start_date->format('d/m/Y') }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">{{ __('client.portal.policy.end_date') }}</p>
                                <p class="text-sm text-gray-700">{{ $policy->end_date->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">{{ __('client.portal.policy.payment_method') }}</p>
                            <p class="text-sm text-gray-700 capitalize">{{ $policy->payment_method }}</p>
                        </div>
                    </div>

                    <!-- Expandable Document Section -->
                    <details class="mt-6 border-t border-gray-200 pt-4">
                        <summary class="cursor-pointer text-sm font-medium text-primary hover:text-primary-focus">
                            {{ __('client.portal.document.title') }}
                        </summary>
                        <div class="mt-2 bg-gray-50 p-4 rounded-lg">
                            <pre
                                class="text-xs text-gray-700 overflow-x-auto whitespace-pre-wrap max-h-48 overflow-y-auto">
                                {!! $this->generateDummyDocument($policy) !!}
                            </pre>
                        </div>
                    </details>
                </div>
            </div>
        @empty
            <div
                class="col-span-full flex flex-col items-center justify-center bg-white shadow-lg rounded-xl p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                <p class="text-xl text-gray-600 mb-4">{{ __('client.portal.no_policies') }}</p>
                <a href="{{ route('insurance.calculator') }}"
                   class="btn btn-primary btn-wide">
                    {{ __('client.portal.get_first_policy') }}
                </a>
            </div>
        @endforelse
    </div>
</div>
