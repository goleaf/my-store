<?php

return [
    'label_plural' => 'Versandmethoden',
    'label' => 'Versandmethode',
    'form' => [
        'name' => [
            'label' => 'Name',
        ],
        'description' => [
            'label' => 'Beschreibung',
        ],
        'code' => [
            'label' => 'Code',
        ],
        'cutoff' => [
            'label' => 'Annahmeschluss',
        ],
        'charge_by' => [
            'label' => 'Berechnen nach',
            'options' => [
                'cart_total' => 'Warenkorbwert',
                'weight' => 'Gewicht',
            ],
        ],
        'driver' => [
            'label' => 'Typ',
            'options' => [
                'ship-by' => 'Standard',
                'collection' => 'Abholung',
            ],
        ],
        'stock_available' => [
            'label' => 'Der Bestand aller Warenkorbartikel muss verfügbar sein',
        ],
    ],
    'table' => [
        'name' => [
            'label' => 'Name',
        ],
        'code' => [
            'label' => 'Code',
        ],
        'driver' => [
            'label' => 'Typ',
            'options' => [
                'ship-by' => 'Standard',
                'collection' => 'Abholung',
            ],
        ],
    ],
    'pages' => [
        'availability' => [
            'label' => 'Verfügbarkeit',
            'customer_groups' => 'Diese Versandmethode ist derzeit in allen Kundengruppen nicht verfügbar.',
        ],
    ],
];
