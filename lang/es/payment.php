<?php

return [
    'title' => 'Proceso de Pago',
    'summary' => [
        'title' => 'Resumen de Precios',
        'monthly' => 'Pago Mensual',
        'annual' => 'Pago Anual',
        'month' => 'mes',
        'year' => 'año',
        'save' => 'Ahorra',
    ],
    'method' => [
        'title' => 'Método de Pago',
        'card' => 'Tarjeta',
        'sepa' => 'Domiciliación Bancaria',
    ],
    'card' => [
        'number' => 'Número de Tarjeta',
        'expiry' => 'Fecha de Caducidad',
        'cvv' => 'CVV',
    ],
    'sepa' => [
        'iban' => 'IBAN',
        'example' => 'Ejemplo: ES91 2100 0418 4502 0005 1332',
    ],
    'actions' => [
        'process' => 'Procesar Pago',
    ],
    'errors' => [
        'card_number_required' => 'El número de tarjeta es obligatorio.',
        'card_expiry_required' => 'La fecha de caducidad es obligatoria.',
        'card_expiry_format' => 'El formato de la fecha de caducidad debe ser MM/YY.',
        'card_cvv_required' => 'El código CVV es obligatorio.',
        'card_cvv_format' => 'El código CVV debe tener 3 o 4 dígitos.',
        'iban_required' => 'El IBAN es obligatorio.',
        'iban_format' => 'El formato del IBAN no es válido.',
        'invalid_card_number' => 'El número de tarjeta no es válido. Por favor, revíselo e intente nuevamente.',
        'processing_failed' => 'Ha ocurrido un error al procesar el pago. Por favor, intente nuevamente.',
    ],
];
