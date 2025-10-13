<?php

use Rpungello\SdkClient\SdkClient;

it('can create clients', function () {
    $client = new Rpungello\SdkClient\SdkClient(new \Rpungello\SdkClient\Drivers\GuzzleDriver('https://example.com'));
    expect($client)->toBeInstanceOf(SdkClient::class);
});

it('can convert json to multipart', function () {
    $json = [
        'foo' => 'bar',
        'bar' => 'baz',
        'true' => true,
        'false' => false,
    ];

    $multipart = SdkClient::convertJsonToMultipart($json);
    expect($multipart)->toHaveCount(4)
        ->and($multipart[0])->toHaveKey('name', 'foo')
        ->and($multipart[0])->toHaveKey('contents', 'bar')
        ->and($multipart[1])->toHaveKey('name', 'bar')
        ->and($multipart[1])->toHaveKey('contents', 'baz')
        ->and($multipart[2])->toHaveKey('name', 'true')
        ->and($multipart[2])->toHaveKey('contents', 'true')
        ->and($multipart[3])->toHaveKey('name', 'false')
        ->and($multipart[3])->toHaveKey('contents', 'false');
});

it('can convert nested json to multipart', function () {
    $json = [
        'foo' => 'bar',
        'bar' => [
            'nest1' => 'value1',
            'nest2' => 'value2',
        ],
    ];

    $multipart = SdkClient::convertJsonToMultipart($json);
    expect($multipart)->toHaveCount(2)
        ->and($multipart[0])->toHaveKey('name', 'foo')
        ->and($multipart[0])->toHaveKey('contents', 'bar')
        ->and($multipart[1])->toHaveKey('name', 'bar')
        ->and($multipart[1])->toHaveKey('contents', serialize($json['bar']));
});
