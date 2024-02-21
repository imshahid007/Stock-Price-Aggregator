<?php

it('has home page working', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
