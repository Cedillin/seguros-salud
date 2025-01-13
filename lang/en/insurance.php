<?php

return [
    'calculator' => [
        'title' => 'Health Insurance Calculator',
        'phone' => 'Phone',
        'birth_date' => 'Birth Date',
        'copay' => 'Include Copay?',
        'add_insured' => 'Add Insured',
        'remove_insured' => 'Remove',
        'calculate' => 'Calculate Price',
    ],
    'summary' => [
        'title' => 'Calculation Summary',
        'base_price' => 'Base Price',
        'discount' => 'Discount (:percentage%)',
        'final_price' => 'Final Price',
    ],
    'errors' => [
        'main_insured_age' => 'Main insured age must be between 18 and 70 years.',
        'additional_insured_age' => 'Additional insured age must be less than 70 years.',
        'calculation_failed' => 'An error occurred while calculating the price. Please try again.',
    ],
];
