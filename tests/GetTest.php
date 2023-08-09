<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rpungello\SdkClient\Tests\Dtos\DummyDto;

it('can make get requests', function () {
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], 'Hello world'),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->get('dummy');
    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getBody()->getContents())->toBe('Hello world');
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
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->getJson('dummy');
    expect($response)->toBeArray();
    expect($response)->toBe($data);
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
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->getDto('dummy', DummyDto::class);
    expect($response)->toBeInstanceOf(DummyDto::class);
    expect($response->id)->toBe(1);
    expect($response->name)->toBe('John Smith');
    expect($response->comment)->toBe('This is a comment');
    expect($response->date)->toBeNull();
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
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->getDtoArray('dummy', DummyDto::class);
    expect($response)->toBeArray();
    expect($response)->toHaveCount(2);

    expect($response[0]->id)->toBe(1);
    expect($response[0]->name)->toBe('John Smith');
    expect($response[0]->comment)->toBe('This is a comment');
    expect($response[0]->date)->toBeNull();

    expect($response[1]->id)->toBe(2);
    expect($response[1]->name)->toBe('Jane Doe');
    expect($response[1]->comment)->toBeNull();
    expect($response[1]->date)->toBeNull();
});
