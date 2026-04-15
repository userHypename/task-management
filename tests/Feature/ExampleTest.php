<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    // The application redirects '/' to '/dashboard'
    $response->assertStatus(302);
});
