<?php

return [
    'title' => 'Payment Process',
    'summary' => [
        'title' => 'Price Summary',
        'monthly' => 'Monthly Payment',
        'annual' => 'Annual Payment',
        'month' => 'month',
        'year' => 'year',
        'save' => 'Save',
    ],
    'method' => [
        'title' => 'Payment Method',
        'card' => 'Card',
        'sepa' => 'Bank Direct Debit',
    ],
    'card' => [
        'number' => 'Card Number',
        'expiry' => 'Expiry Date',
        'cvv' => 'CVV',
    ],
    'sepa' => [
        'iban' => 'IBAN',
        'example' => 'Example: ES91 2100 0418 4502 0005 1332',
    ],
    'actions' => [
        'process' => 'Process Payment',
    ],
    'errors' => [
        'card_number_required' => 'Card number is required.',
        'card_expiry_required' => 'Expiration date is required.',
        'card_expiry_format' => 'Expiration date format must be MM/YY.',
        'card_cvv_required' => 'CVV code is required.',
        'card_cvv_format' => 'CVV code must be 3 or 4 digits.',
        'iban_required' => 'IBAN is required.',
        'iban_format' => 'IBAN format is invalid.',
        'invalid_card_number' => 'The card number is invalid. Please check and try again.',
        'processing_failed' => 'An error occurred while processing the payment. Please try again.',
    ],
];
