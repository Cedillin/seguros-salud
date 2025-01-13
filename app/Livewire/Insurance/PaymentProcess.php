<?php

declare(strict_types=1);

namespace App\Livewire\Insurance;

use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Rule;
use Livewire\Component;

class PaymentProcess extends Component
{
    public Lead $lead;

    #[Rule('required|in:monthly,annual')]
    public string $payment_frequency = 'monthly';

    #[Rule('required|in:card,sepa')]
    public string $payment_method = 'card';

    // Card payment fields
    #[Rule('required_if:payment_method,card')]
    public ?string $card_number = null;

    #[Rule('required_if:payment_method,card|date_format:m/y')]
    public ?string $card_expiry = null;

    #[Rule('required_if:payment_method,card|numeric|digits:3,4')]
    public ?string $card_cvv = null;

    // SEPA fields
    #[Rule('required_if:payment_method,sepa|regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/')]
    public ?string $iban = null;

    public float $monthly_price;

    public float $annual_price;

    /**
     * Get custom validation rules based on payment method
     */
    protected function rules(): array
    {
        return [
            'payment_frequency' => 'required|in:monthly,annual',
            'payment_method' => 'required|in:card,sepa',

            // Card fields
            'card_number' => $this->payment_method === 'card' ? 'required|string' : 'nullable',
            'card_expiry' => $this->payment_method === 'card' ? 'required|date_format:m/y' : 'nullable',
            'card_cvv' => $this->payment_method === 'card' ? 'required|numeric|digits:3,4' : 'nullable',

            // SEPA fields
            'iban' => $this->payment_method === 'sepa' ? 'required|regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/' : 'nullable',
        ];
    }

    /**
     * Get custom validation messages
     */
    protected function messages(): array
    {
        return [
            'card_number.required' => __('payment.errors.card_number_required'),
            'card_expiry.required' => __('payment.errors.card_expiry_required'),
            'card_expiry.date_format' => __('payment.errors.card_expiry_format'),
            'card_cvv.required' => __('payment.errors.card_cvv_required'),
            'card_cvv.digits' => __('payment.errors.card_cvv_format'),
            'iban.required' => __('payment.errors.iban_required'),
            'iban.regex' => __('payment.errors.iban_format'),
        ];
    }

    /**
     * Initialize the payment process
     */
    public function mount(Lead $lead): ?RedirectResponse
    {
        $this->lead = $lead;

        // Verify lead belongs to authenticated user
        if ($this->lead->user_id !== auth()->id()) {
            return to_route('insurance.calculator');
        }

        // Calculate prices
        $this->monthly_price = (float) $this->lead->calculated_price;
        $this->annual_price = (float) $this->lead->calculated_price * 12 * 0.95;

        return null;
    }

    /**
     * Process the payment details
     */
    public function process()
    {
        $this->validate($this->rules(), $this->messages());

        try {
            $paymentDetails = $this->payment_method === 'card'
                ? $this->processCardPayment()
                : $this->processSepaPayment();

            // Update lead with payment information
            $this->lead->update([
                'payment_method' => $this->payment_method,
                'payment_frequency' => $this->payment_frequency,
                'payment_details' => $paymentDetails,
                'final_price' => $this->payment_frequency === 'monthly'
                    ? $this->monthly_price
                    : $this->annual_price,
                'payment_completed' => true,
            ]);

            return to_route('signature.process', ['lead' => $this->lead]);

        } catch (\Exception $e) {
            logger()->error('Payment processing error:', [
                'error' => $e->getMessage(),
                'lead_id' => $this->lead->id,
            ]);

            session()->flash('error', __('payment.errors.processing_failed'));

            return null;
        }
    }

    /**
     * Process card payment details
     * @throws \Exception
     */
    private function processCardPayment(): array
    {
        // Validate card format (basic validation)
        if (!$this->validateCardNumber($this->card_number)) {
            logger()->info('Invalid card number detected', [
                'card_number' => substr($this->card_number ?? '', -4)
            ]);
            throw new \Exception(__('payment.errors.invalid_card_number'));
        }

        // In a real application, we would integrate with a payment provider here
        return [
            'type' => 'card',
            'last_four' => substr($this->card_number, -4),
            'expiry' => $this->card_expiry,
        ];
    }

    /**
     * Process SEPA payment details
     */
    private function processSepaPayment(): array
    {
        // In a real application, we would integrate with a payment provider here
        return [
            'type' => 'sepa',
            'iban' => $this->iban,
            'masked_iban' => $this->maskIban($this->iban),
        ];
    }

    /**
     * Validate card number using Luhn algorithm
     */
    private function validateCardNumber(?string $number): bool
    {
        if (empty($number)) {
            return false;
        }

        // Remove any non-digits
        $number = preg_replace('/\D/', '', $number);

        // Check if we have a valid length
        if (strlen($number) < 13 || strlen($number) > 19) {
            return false;
        }

        $sum = 0;
        $length = strlen($number);
        $parity = $length % 2;

        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = (int)$number[$i];
            if ($i % 2 === $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }

        return ($sum % 10) === 0;
    }

    /**
     * Mask IBAN for display/storage
     */
    private function maskIban(string $iban): string
    {
        $length = strlen($iban);

        return substr($iban, 0, 4) . str_repeat('*', $length - 8) . substr($iban, -4);
    }

    /**
     * Render the payment process view
     */
    public function render(): View
    {
        return view('livewire.insurance.payment-process');
    }
}
