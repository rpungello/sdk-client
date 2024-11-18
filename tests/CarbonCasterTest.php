<?php

use Carbon\Carbon;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rpungello\SdkClient\Casters\CarbonCaster;
use Rpungello\SdkClient\Tests\Dtos\DummyDtoCarbon;

it('can convert valid dates', function () {
    $caster = new CarbonCaster([Carbon::class]);
    $dateString = '2023-01-01';
    $date = $caster->cast($dateString);
    expect($date)->toBeInstanceOf(Carbon::class);
    expect($date->format('Y-m-d'))->toBe($dateString);
});

it('can convert empty dates', function () {
    $caster = new CarbonCaster([Carbon::class]);
    $dateString = '';
    $date = $caster->cast($dateString);
    expect($date)->toBeNull();
});

it('can convert null dates', function () {
    $caster = new CarbonCaster([Carbon::class]);
    $date = $caster->cast(null);
    expect($date)->toBeNull();
});

it('can convert valid dates with custom formats', function () {
    $caster = new CarbonCaster([Carbon::class], 'm/d/Y');
    $dateString = '01/01/2023';
    $date = $caster->cast($dateString);
    expect($date)->toBeInstanceOf(Carbon::class);
    expect($date->format('Y-m-d'))->toBe('2023-01-01');
});

it('fails to convert invalid dates', function () {
    $caster = new CarbonCaster([Carbon::class]);
    $dateString = '2023abc';
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
    $response = $client->getDto('dummy', DummyDtoCarbon::class);
    expect($response)->toBeInstanceOf(DummyDtoCarbon::class);
    expect($response->id)->toBe(1);
    expect($response->name)->toBe('John Smith');
    expect($response->comment)->toBe('This is a comment');
    expect($response->date)->toBeInstanceOf(Carbon::class);
    expect($response->date->format('Y-m-d'))->toBe('2023-01-01');
});

it('can make get dto requests with dates and custom formats', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
        'date_with_format' => '01/01/2023',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->getDto('dummy', DummyDtoCarbon::class);
    expect($response)->toBeInstanceOf(DummyDtoCarbon::class);
    expect($response->id)->toBe(1);
    expect($response->name)->toBe('John Smith');
    expect($response->comment)->toBe('This is a comment');
    expect($response->date_with_format)->toBeInstanceOf(Carbon::class);
    expect($response->date_with_format->format('Y-m-d'))->toBe('2023-01-01');
});

it('fails making get dto requests with dates and custom formats with invalid dates', function () {
    $data = [
        'id' => 1,
        'name' => 'John Smith',
        'comment' => 'This is a comment',
        'date_with_format' => '2023-01-01',
    ];
    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($data)),
    ]);
    $client = new Rpungello\SdkClient\SdkClient('https://example.com', HandlerStack::create($mock));
    $response = $client->getDto('dummy', DummyDtoCarbon::class);
    expect($response)->toBeInstanceOf(DummyDtoCarbon::class);
    expect($response->id)->toBe(1);
    expect($response->name)->toBe('John Smith');
    expect($response->comment)->toBe('This is a comment');
    expect($response->date_with_format)->toBeNull();
});
