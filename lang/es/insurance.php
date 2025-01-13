<?php

return [
    'calculator' => [
        'title' => 'Calculadora de Seguro de Salud',
        'phone' => 'Teléfono',
        'birth_date' => 'Fecha de Nacimiento',
        'copay' => '¿Incluir copago?',
        'add_insured' => 'Añadir Asegurado',
        'remove_insured' => 'Eliminar',
        'calculate' => 'Calcular Precio',
    ],
    'summary' => [
        'title' => 'Resumen del Cálculo',
        'base_price' => 'Precio Base',
        'discount' => 'Descuento (:percentage%)',
        'final_price' => 'Precio Final',
    ],
    'errors' => [
        'main_insured_age' => 'La edad del asegurado principal debe estar entre 18 y 70 años.',
        'additional_insured_age' => 'La edad de los asegurados adicionales debe ser menor a 70 años.',
        'calculation_failed' => 'Ha ocurrido un error al calcular el precio. Por favor, intente nuevamente.',
    ],
];
