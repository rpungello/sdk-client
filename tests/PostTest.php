<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rpungello\SdkClient\Tests\Dtos\DummyDto;

it('can make post requests', function () {
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], 'Hello world'),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->post('dummy');
    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getBody()->getContents())->toBe('Hello world');
});

it('can make post json requests', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->postJson('dummy', $data);
    expect($response)->toBeArray();
    expect($response)->toBe($data);
});

it('can make post dto requests', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $headers = ['http_errors' => false];
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->postDto('dummy', new DummyDto($data), $headers);
    expect($response)->toBeInstanceOf(DummyDto::class);
    expect($response->id)->toBe(1);
    expect($response->name)->toBe('John Smith');
    expect($response->comment)->toBe('This is a comment');
    expect($response->date)->toBeNull();
});

it('can make post json to dto requests', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $headers = ['http_errors' => false];
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->postJsonAsDto('dummy', $data, DummyDto::class, $headers);
    expect($response)->toBeInstanceOf(DummyDto::class)
        ->and($response->id)->toBe(1)
        ->and($response->name)->toBe('John Smith')
        ->and($response->comment)->toBe('This is a comment')
        ->and($response->date)->toBeNull();
});

it('can make post multipart requests', function () {
    $tempDir = sys_get_temp_dir();
    $tempFile = "/$tempDir/test.txt";
    file_put_contents($tempFile, 'Hello world');
    $data = [
        'id' => 1,
        'name' => 'Test Report',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $headers = ['http_errors' => false];
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->postMultipart('dummy', [[
        'name' => 'file',
        'contents' => fopen($tempFile, 'r'),
    ]], $headers);

    expect($response->getBody()->getContents())->toBe('{"id":1,"name":"Test Report"}');
});
