<?php

return [
    'label' => 'Verzendzone',
    'label_plural' => 'Verzendzones',
    'form' => [
        'unrestricted' => [
            'content' => 'Deze verzendzone heeft geen beperkingen en is beschikbaar voor alle klanten tijdens het afrekenen.',
        ],
        'name' => [
            'label' => 'Naam',
        ],
        'type' => [
            'label' => 'Type',
            'options' => [
                'unrestricted' => 'Onbeperkt',
                'countries' => 'Beperk tot landen',
                'states' => 'Beperk tot staten / provincies',
                'postcodes' => 'Beperk tot postcodes',
            ],
        ],
        'country' => [
            'label' => 'Land',
        ],
        'states' => [
            'label' => 'Staten / provincies',
        ],
        'countries' => [
            'label' => 'Landen',
        ],
        'postcodes' => [
            'label' => 'Postcodes',
            'helper' => 'Zet elke postcode op een nieuwe regel. Jokers zoals NW* worden ondersteund.',
        ],
    ],
    'table' => [
        'name' => [
            'label' => 'Naam',
        ],
        'type' => [
            'label' => 'Type',
            'options' => [
                'unrestricted' => 'Onbeperkt',
                'countries' => 'Beperk tot landen',
                'states' => 'Beperk tot staten / provincies',
                'postcodes' => 'Beperk tot postcodes',
            ],
        ],
    ],
];
