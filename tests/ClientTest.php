<?php

use Rpungello\SdkClient\SdkClient;

it('can create clients', function () {
    $client = new Rpungello\SdkClient\SdkClient('https://example.com');
    expect($client)->toBeInstanceOf(SdkClient::class);
});

