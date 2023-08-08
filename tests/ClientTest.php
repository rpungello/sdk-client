<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rpungello\SdkClient\SdkClient;
use Rpungello\SdkClient\Tests\Dtos\DummyDto;

it('can create clients', function () {
    $client = new Rpungello\SdkClient\SdkClient('https://example.com');
    expect($client)->toBeInstanceOf(SdkClient::class);
});

it('can make get requests', function () {
    $mockDto = new DummyDto(
        id: 1,
        name: 'John Smith',
        comment: 'This is a comment'
    );
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($mockDto->toArray())),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->get('dummy');
    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getBody()->getContents())->toBe('{"id":1,"name":"John Smith","comment":"This is a comment"}');
});

it('can make get json requests', function () {
    $mockDto = new DummyDto(
        id: 1,
        name: 'John Smith',
        comment: 'This is a comment'
    );
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($mockDto->toArray())),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->getJson('dummy');
    expect($response)->toBeArray();
    expect($response)->toBe($mockDto->toArray());
});

it('can make get dto requests', function () {
    $mockDto = new DummyDto(
        id: 1,
        name: 'John Smith',
        comment: 'This is a comment'
    );
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($mockDto->toArray())),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->getDto('dummy', DummyDto::class);
    expect($response)->toBeInstanceOf(DummyDto::class);
    expect($response->id)->toBe(1);
    expect($response->name)->toBe('John Smith');
    expect($response->comment)->toBe('This is a comment');
});
