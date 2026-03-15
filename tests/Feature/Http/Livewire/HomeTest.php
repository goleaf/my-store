<?php

test('example', function () {
    $this->get('/')
        ->assertStatus(200)
        ->assertSeeLivewire('home')
        ->assertSeeLivewire('components.navigation');
});