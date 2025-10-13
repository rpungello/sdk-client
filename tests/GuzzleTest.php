<?php

use Rpungello\SdkClient\Drivers\GuzzleDriver;

it('can construct uris', function () {
    $driver = new GuzzleDriver('https://www.example.com');
    expect($driver->getRelativeUri('/endpoint', ['foo' => 'bar']))->toBe('https://www.example.com/endpoint?foo=bar');
});
