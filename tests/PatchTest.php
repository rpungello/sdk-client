<?php

use GuzzleHttp\Handler\MockHandler;
use Rpungello\SdkClient\SdkClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use Rpungello\SdkClient\Tests\Dtos\DummyDto;

it('should make patch request', function () {
    $baseUri = 'https://httpbin.org/status/200';
    $mockHandler = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], 'Hello world'),
    ]);
    $client = new SdkClient($baseUri, HandlerStack::create($mockHandler));
    $response = $client->patch('foo');
    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getBody()->getContents())->toBe('Hello world');
});

it('should make patch requests using json', function () {
    $baseUri = 'https://httpbin.org/status/200';
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
    ];
    $mockHandler = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $client = new SdkClient($baseUri, HandlerStack::create($mockHandler));
    $response = $client->patchJson('foo', $data);
    expect($response)->toBeArray();
    expect($response)->toBe($data);
});

it('should make patch requests using DTO', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->patchDto('dummy', new DummyDto($data));
    expect($response)->toBeInstanceOf(DummyDto::class);
    expect($response->id)->toBe(1);
    expect($response->name)->toBe('John Smith');
    expect($response->comment)->toBe('This is a comment');
    expect($response->date)->toBeNull();
});
