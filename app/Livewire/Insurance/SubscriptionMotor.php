<?php

declare(strict_types=1);

namespace App\Livewire\Insurance;

use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class SubscriptionMotor extends Component
{
    public Lead $lead;

    #[Rule('required|boolean')]
    public bool $is_smoker = false;

    /**
     * Process subscription answers and determine if user can proceed
     */
    public function process()
    {
        $this->validate();

        try {
            if ($this->is_smoker) {
                $this->lead->update([
                    'status' => 'rejected',
                    'is_smoker' => true,
                ]);

                return to_route('subscription.rejected');
            }

            $this->lead->update([
                'status' => 'completed',
                'is_smoker' => false,
            ]);

            return to_route('payment.process', ['lead' => $this->lead]);

        } catch (\Exception $e) {
            logger()->error('Subscription processing error:', [
                'error' => $e->getMessage(),
                'lead_id' => $this->lead->id
            ]);
            session()->flash('error', __('subscription.errors.processing_failed'));

            return null;
        }
    }

    /**
     * Render the subscription motor component
     */
    public function render(): View
    {
        return view('livewire.insurance.subscription-motor');
    }
}
