<?php

namespace Rpungello\SdkClient\Tests\Dtos;

use Carbon\Carbon;
use Rpungello\SdkClient\Casters\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class DummyDtoCarbon extends DataTransferObject
{
    public int $id;
    public string $name;
    public ?string $comment;
    #[CastWith(CarbonCaster::class)]
    public ?Carbon $date;
    #[CastWith(CarbonCaster::class, format: 'm/d/Y')]
    public ?Carbon $date_with_format;
}
