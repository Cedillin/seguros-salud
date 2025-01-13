<?php

namespace App\Services;

use DateTime;

class InsuranceCalculator
{
    /**
     * Calculates the actuarial age based on the birthdate.
     *
     * @throws \Exception
     */
    public function calculateActuarialAge(string $birthDate): int
    {
        $birthday = new DateTime($birthDate);
        $today = new DateTime;

        $nextBirthday = (clone $birthday)->modify('+1 year');
        $lastBirthday = clone $birthday;

        $daysToNext = $nextBirthday->diff($today)->days;
        $daysSinceLast = $today->diff($lastBirthday)->days;

        $currentAge = $today->diff($birthday)->y;

        return $daysToNext < $daysSinceLast ? $currentAge + 1 : $currentAge;
    }

    /**
     * Calculates the base price based on actuarial age and copay selection.
     */
    public function calculateBasePrice(int $actuarialAge, bool $hasCopay): float
    {
        $basePrice = match (true) {
            $actuarialAge < 30 => 50.0,
            $actuarialAge < 40 => 60.0,
            $actuarialAge < 50 => 75.0,
            $actuarialAge < 60 => 90.0,
            default => 120.0,
        };

        return $hasCopay ? $basePrice * 0.8 : $basePrice;
    }

    /**
     * Calculates the total price for the main insured and additional insured persons.
     * Applies discounts based on the number of insured persons.
     *
     * @throws \Exception
     */
    public function calculateTotalPrice(array $data): array
    {
        $totalPrice = 0.0;
        $insuredCount = 1;
        $details = [];

        // Calculate main insured price
        $mainAge = $this->calculateActuarialAge($data['main_insured_birth_date']);
        $mainPrice = $this->calculateBasePrice($mainAge, $data['has_copay']);
        $totalPrice += $mainPrice;
        $details['main_insured'] = [
            'age' => $mainAge,
            'price' => $mainPrice,
        ];

        // Calculate additional insured prices
        if (! empty($data['additional_insured'])) {
            foreach ($data['additional_insured'] as $insured) {
                if (! empty($insured['birth_date'])) {
                    $age = $this->calculateActuarialAge($insured['birth_date']);
                    $price = $this->calculateBasePrice($age, $data['has_copay']);
                    $totalPrice += $price;
                    $details['additional_insured'][] = [
                        'age' => $age,
                        'price' => $price,
                    ];
                    $insuredCount++;
                }
            }
        }

        // Apply discount based on the number of insured persons
        $discount = match ($insuredCount) {
            2 => 0.10, // 10% discount
            3 => 0.20, // 20% discount
            default => 0.0
        };

        $finalPrice = $totalPrice * (1 - $discount);

        return [
            'base_price' => $totalPrice,
            'discount_percentage' => $discount * 100,
            'discount_amount' => $totalPrice * $discount,
            'final_price' => $finalPrice,
            'details' => $details,
        ];
    }
}
