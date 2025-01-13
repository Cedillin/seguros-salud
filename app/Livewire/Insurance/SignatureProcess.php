<?php

declare(strict_types=1);

namespace App\Livewire\Insurance;

use App\Models\Lead;
use App\Models\Policy;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Livewire\Component;

class SignatureProcess extends Component
{
    public Lead $lead;
    public bool $accepted = false;
    public ?string $signed_at = null;
    public ?string $dummy_document = null;

    /**
     * Initialize the signature process
     */
    public function mount(Lead $lead)
    {
        $this->lead = $lead;

        // Verify lead belongs to authenticated user
        if ($this->lead->user_id !== auth()->id()) {
            return redirect()->route('insurance.calculator');
        }

        // If already signed, redirect to portal
        if ($this->lead->document_signed) {
            return redirect()->route('client.portal');
        }

        // If payment not completed, redirect back to payment
        if (!$this->lead->payment_completed) {
            return redirect()->route('payment.process', ['lead' => $this->lead]);
        }

        // Generate dummy document content
        $this->dummy_document = $this->generateDummyDocument();

        return null;
    }

    /**
     * Process the signature
     */
    public function sign()
    {
        try {
            $this->validate([
                'accepted' => 'required|accepted'
            ]);

            // Log data before policy creation
            logger()->info('Attempting to create policy:', [
                'user_id' => $this->lead->user_id,
                'lead_id' => $this->lead->id,
                'final_price' => $this->lead->final_price,
                'payment_frequency' => $this->lead->payment_frequency,
                'payment_method' => $this->lead->payment_method,
            ]);

            // Create policy with data validation
            $policyData = [
                'user_id' => $this->lead->user_id,
                'lead_id' => $this->lead->id,
                'policy_number' => $this->generatePolicyNumber(),
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'premium_amount' => (float) $this->lead->final_price,
                'payment_frequency' => $this->lead->payment_frequency,
                'payment_method' => $this->lead->payment_method,
                'payment_details' => $this->lead->payment_details,
                'signed_document_path' => 'documents/dummy.pdf',
                'status' => 'active'
            ];

            // Validate all required fields are present
            foreach ($policyData as $key => $value) {
                if ($value === null) {
                    throw new \Exception("Missing required field: {$key}");
                }
            }

            $policy = Policy::create($policyData);

            logger()->info('Policy created successfully:', [
                'policy_id' => $policy->id,
                'policy_number' => $policy->policy_number,
            ]);

            // Update lead status
            $this->lead->update([
                'document_signed' => true,
                'signed_at' => now()
            ]);

            return to_route('client.portal');

        } catch (\Exception $e) {
            logger()->error('Signature processing error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'lead_id' => $this->lead->id ?? 'no_lead',
                'user_id' => auth()->id(),
            ]);

            session()->flash('error', __('signature.errors.processing_failed'));
            return null;
        }
    }
    /**
     * Generate a dummy document for signing
     */
    private function generateDummyDocument(): string
    {
        return "INSURANCE POLICY DOCUMENT\n\n" .
            "Policy Details:\n" .
            "- Insured: " . auth()->user()->name . "\n" .
            "- Premium: €" . number_format((float) $this->lead->final_price, 2) . "\n" .
            "- Payment Frequency: " . ucfirst($this->lead->payment_frequency) . "\n" .
            "- Start Date: " . now()->format('Y-m-d') . "\n" .
            "- End Date: " . now()->addYear()->format('Y-m-d') . "\n\n" .
            "Terms and Conditions...";
    }

    /**
     * Generate a unique policy number
     */
    private function generatePolicyNumber(): string
    {
        return 'POL-' . strtoupper(Str::random(8));
    }

    /**
     * Render the signature process view
     */
    public function render(): View
    {
        return view('livewire.insurance.signature-process');
    }
}
