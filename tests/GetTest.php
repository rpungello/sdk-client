<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rpungello\SdkClient\Tests\Dtos\DummyDto;

it('can make get requests', function () {
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], 'Hello world'),
    ]);
    $headers = ['http_errors' => false];
    $client = new Rpungello\SdkClient\SdkClient(new \Rpungello\SdkClient\Drivers\GuzzleDriver('https://example.com', HandlerStack::create($mock)));
    $response = $client->get('dummy', [], $headers);
    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->getBody()->getContents())->toBe('Hello world');
});

it('can make get json requests', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $client = new Rpungello\SdkClient\SdkClient(new \Rpungello\SdkClient\Drivers\GuzzleDriver('https://example.com', HandlerStack::create($mock)));
    $response = $client->getJson('dummy');
    expect($response)->toBeArray()
        ->and($response)->toBe($data);
});

it('can make get dto requests', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $client = new Rpungello\SdkClient\SdkClient(new \Rpungello\SdkClient\Drivers\GuzzleDriver('https://example.com', HandlerStack::create($mock)));
    $response = $client->getDto('dummy', DummyDto::class);
    expect($response)->toBeInstanceOf(DummyDto::class)
        ->and($response->id)->toBe(1)
        ->and($response->name)->toBe('John Smith')
        ->and($response->comment)->toBe('This is a comment')
        ->and($response->date)->toBeNull();
});

it('can make get dto array requests', function () {
    $data = [
        [
            'id' => 1,
            'name' => 'John Smith',
            'comment' => 'This is a comment',
        ],
        [
            'id' => 2,
            'name' => 'Jane Doe',
        ],
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $client = new Rpungello\SdkClient\SdkClient(new \Rpungello\SdkClient\Drivers\GuzzleDriver('https://example.com', HandlerStack::create($mock)));
    $response = $client->getDtoArray('dummy', DummyDto::class);
    expect($response)->toBeArray()
        ->and($response)->toHaveCount(2)
        ->and($response[0]->id)->toBe(1)
        ->and($response[0]->name)->toBe('John Smith')
        ->and($response[0]->comment)->toBe('This is a comment')
        ->and($response[0]->date)->toBeNull()
        ->and($response[1]->id)->toBe(2)
        ->and($response[1]->name)->toBe('Jane Doe')
        ->and($response[1]->comment)->toBeNull()
        ->and($response[1]->date)->toBeNull();

});
