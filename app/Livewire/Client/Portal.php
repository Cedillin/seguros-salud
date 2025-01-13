<?php

declare(strict_types=1);

namespace App\Livewire\Client;

use App\Models\Policy;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class Portal extends Component
{
    public ?Policy $policy = null;
    public ?string $dummy_document = null;
    public $policies;

    /**
     * Initialize the client portal
     */
    public function mount()
    {
        $this->policies = auth()
            ->user()
            ->policies()
            ->with(['lead'])
            ->latest()
            ->get();
    }

    /**
     * Generate the dummy document content
     */
    public function generateDummyDocument(Policy $policy): string
    {
        return "INSURANCE POLICY DOCUMENT\n\n" .
            "Policy Details:\n" .
            "- Policy Number: " . $policy->policy_number . "\n" .
            "- Insured: " . auth()->user()->name . "\n" .
            "- Premium: €" . number_format($policy->premium_amount, 2) . "\n" .
            "- Payment Frequency: " . ucfirst($policy->payment_frequency) . "\n" .
            "- Start Date: " . $policy->start_date->format('d/m/Y') . "\n" .
            "- End Date: " . $policy->end_date->format('d/m/Y') . "\n\n" .
            "Terms and Conditions...";
    }

    /**
     * Render the client portal view
     */
    public function render(): View
    {
        return view('livewire.client.portal');
    }
}
