<?php

return [
    'label_plural' => 'Verzendmethoden',
    'label' => 'Verzendmethode',
    'form' => [
        'name' => [
            'label' => 'Naam',
        ],
        'description' => [
            'label' => 'Beschrijving',
        ],
        'code' => [
            'label' => 'Code',
        ],
        'cutoff' => [
            'label' => 'Sluitingstijd',
        ],
        'charge_by' => [
            'label' => 'Berekenen op basis van',
            'options' => [
                'cart_total' => 'Winkelwagentotaal',
                'weight' => 'Gewicht',
            ],
        ],
        'driver' => [
            'label' => 'Type',
            'options' => [
                'ship-by' => 'Standaard',
                'collection' => 'Afhalen',
            ],
        ],
        'stock_available' => [
            'label' => 'Voorraad van alle winkelwagenartikelen moet beschikbaar zijn',
        ],
    ],
    'table' => [
        'name' => [
            'label' => 'Naam',
        ],
        'code' => [
            'label' => 'Code',
        ],
        'driver' => [
            'label' => 'Type',
            'options' => [
                'ship-by' => 'Standaard',
                'collection' => 'Afhalen',
            ],
        ],
    ],
    'pages' => [
        'availability' => [
            'label' => 'Beschikbaarheid',
            'customer_groups' => 'Deze verzendmethode is momenteel niet beschikbaar voor alle klantengroepen.',
        ],
    ],
];
