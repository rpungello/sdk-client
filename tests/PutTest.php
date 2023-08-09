<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rpungello\SdkClient\Tests\Dtos\DummyDto;

it('can make put requests', function () {
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], 'Hello world'),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->put('dummy');
    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getBody()->getContents())->toBe('Hello world');
});

it('can make put json requests', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->putJson('dummy', $data);
    expect($response)->toBeArray();
    expect($response)->toBe($data);
});

it('can make put dto requests', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->putDto('dummy', new DummyDto($data));
    expect($response)->toBeInstanceOf(DummyDto::class);
    expect($response->id)->toBe(1);
    expect($response->name)->toBe('John Smith');
    expect($response->comment)->toBe('This is a comment');
    expect($response->date)->toBeNull();
});
