<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rpungello\SdkClient\Casters\DateTimeCaster;
use Rpungello\SdkClient\Tests\Dtos\DummyDto;

it('can convert valid dates', function () {
    $caster = new DateTimeCaster([DateTimeImmutable::class]);
    $dateString = '2023-01-01';
    $date = $caster->cast($dateString);
    expect($date)->toBeInstanceOf(DateTimeImmutable::class);
    expect($date->format('Y-m-d'))->toBe($dateString);
});

it('can convert valid dates with custom formats', function () {
    $caster = new DateTimeCaster([DateTimeImmutable::class], 'm/d/Y');
    $dateString = '01/01/2023';
    $date = $caster->cast($dateString);
    expect($date)->toBeInstanceOf(DateTimeImmutable::class);
    expect($date->format('Y-m-d'))->toBe('2023-01-01');
});

it('fails to convert invalid dates', function () {
    $caster = new DateTimeCaster([DateTimeImmutable::class]);
    $dateString = '2023-01-0a';
    $date = $caster->cast($dateString);
    expect($date)->toBeNull();
});

it('can make get dto requests with dates', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
        'date' => '2023-01-01',
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
    expect($response->date)->toBeInstanceOf(DateTimeImmutable::class);
    expect($response->date->format('Y-m-d'))->toBe('2023-01-01');
});
