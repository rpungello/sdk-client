<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rpungello\SdkClient\Tests\Dtos\DummyDto;

it('can make put requests', function () {
    $mockDto = new DummyDto(
        id: 1,
        name: 'John Smith',
        comment: 'This is a comment'
    );
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($mockDto->toArray())),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->put('dummy', $mockDto);
    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getBody()->getContents())->toBe('{"id":1,"name":"John Smith","comment":"This is a comment"}');
});

it('can make put json requests', function () {
    $mockDto = new DummyDto(
        id: 1,
        name: 'John Smith',
        comment: 'This is a comment'
    );
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($mockDto->toArray())),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->putJson('dummy', $mockDto);
    expect($response)->toBeArray();
    expect($response)->toBe($mockDto->toArray());
});

it('can make put dto requests', function () {
    $mockDto = new DummyDto(
        id: 1,
        name: 'John Smith',
        comment: 'This is a comment'
    );
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($mockDto->toArray())),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->putDto('dummy', $mockDto);
    expect($response)->toBeInstanceOf(DummyDto::class);
    expect($response->id)->toBe(1);
    expect($response->name)->toBe('John Smith');
    expect($response->comment)->toBe('This is a comment');
});
