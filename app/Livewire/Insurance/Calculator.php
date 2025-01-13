<?php

declare(strict_types=1);

namespace App\Livewire\Insurance;

use App\Services\InsuranceCalculator;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Calculator extends Component
{
    #[Rule('required|string|max:15')]
    public string $phone = '';

    #[Rule('required|date')]
    public string $main_insured_birth_date = '';

    #[Rule('required|boolean')]
    public bool $has_copay = false;

    public array $additional_insured = [];

    public ?array $calculation_result = null;

    public ?string $error_message = null;

    protected $rules = [
        'additional_insured.*.birth_date' => 'required|date',
    ];

    /**
     * Initialize the component and check for active leads
     * If user has an active lead, redirect to subscription motor
     */
    public function mount()
    {
        if (auth()->user()->hasActiveLead()) {
            $activeLead = auth()->user()->getActiveLeadAttribute();

            if ($activeLead) {
                return redirect()->route('subscription.motor', ['lead' => $activeLead]);
            }
        }

        return null;
    }

    /**
     * Add a new insured person to the calculation
     * Maximum of 2 additional insured people allowed
     */
    public function addInsured(): void
    {
        if (count($this->additional_insured) < 2) {
            $this->additional_insured[] = ['birth_date' => ''];
        }
    }

    /**
     * Remove an insured person from the calculation
     */
    public function removeInsured(int $index): void
    {
        unset($this->additional_insured[$index]);
        $this->additional_insured = array_values($this->additional_insured);
    }

    /**
     * Calculate insurance price based on provided data
     * Validates ages and creates a lead if calculation is successful
     */
    public function calculate(): void
    {
        $this->validate();

        $calculator = new InsuranceCalculator;

        try {
            // Check main insured actuarial age
            $mainAge = $calculator->calculateActuarialAge($this->main_insured_birth_date);

            if ($mainAge < 18 || $mainAge > 70) {
                $this->error_message = __('insurance.errors.main_insured_age');

                return;
            }

            // Check additional insured ages
            foreach ($this->additional_insured as $insured) {
                $age = $calculator->calculateActuarialAge($insured['birth_date']);
                if ($age < 0 || $age > 70) {
                    $this->error_message = __('insurance.errors.additional_insured_age');

                    return;
                }
            }

            $this->calculation_result = $calculator->calculateTotalPrice([
                'main_insured_birth_date' => $this->main_insured_birth_date,
                'has_copay' => $this->has_copay,
                'additional_insured' => $this->additional_insured,
            ]);

            // Save lead in database
            $lead = auth()->user()->leads()->create([
                'phone' => $this->phone,
                'main_insured_birth_date' => $this->main_insured_birth_date,
                'has_copay' => $this->has_copay,
                'additional_insured' => $this->additional_insured,
                'calculated_price' => $this->calculation_result['final_price'],
                'status' => 'pending',
            ]);

            $this->redirect(route('subscription.motor', ['lead' => $lead->id]));

        } catch (\Exception $e) {
            $this->error_message = __('insurance.errors.calculation_failed');
            logger()->error('Insurance calculation error:', ['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.insurance.calculator');
    }
}
