<?php

return [
    'label' => 'Versandzone',
    'label_plural' => 'Versandzonen',
    'form' => [
        'unrestricted' => [
            'content' => 'Diese Versandzone hat keine Einschränkungen und steht allen Kunden beim Checkout zur Verfügung.',
        ],
        'name' => [
            'label' => 'Name',
        ],
        'type' => [
            'label' => 'Typ',
            'options' => [
                'unrestricted' => 'Uneingeschränkt',
                'countries' => 'Auf Länder beschränken',
                'states' => 'Auf Bundesländer / Provinzen beschränken',
                'postcodes' => 'Auf Postleitzahlen beschränken',
            ],
        ],
        'country' => [
            'label' => 'Land',
        ],
        'states' => [
            'label' => 'Bundesländer / Provinzen',
        ],
        'countries' => [
            'label' => 'Länder',
        ],
        'postcodes' => [
            'label' => 'Postleitzahlen',
            'helper' => 'Jede Postleitzahl in eine neue Zeile schreiben. Platzhalter wie NW* werden unterstützt.',
        ],
    ],
    'table' => [
        'name' => [
            'label' => 'Name',
        ],
        'type' => [
            'label' => 'Typ',
            'options' => [
                'unrestricted' => 'Uneingeschränkt',
                'countries' => 'Auf Länder beschränken',
                'states' => 'Auf Bundesländer / Provinzen beschränken',
                'postcodes' => 'Auf Postleitzahlen beschränken',
            ],
        ],
    ],
];
