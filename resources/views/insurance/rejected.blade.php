<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <div class="text-center">
            <div class="mb-4 text-red-500">
                <svg class="w-16 h-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <h2 class="text-2xl text-black font-semibold mb-4">
                {{ __('subscription.rejected.title') }}
            </h2>

            <p class="text-black mb-6">
                {{ __('subscription.rejected.message') }}
            </p>

            <a href="{{ route('client.portal') }}" class="btn btn-primary">
                {{ __('common.back_to_dashboard') }}
            </a>
        </div>
    </div>
</x-app-layout>
