<?php

namespace Rpungello\SdkClient\Tests\Dtos;

use Carbon\Carbon;
use Rpungello\SdkClient\Casters\CarbonCaster;
use Rpungello\SdkClient\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;

class DummyDtoCarbon extends DataTransferObject
{
    public int $id;
    public string $name;
    public ?string $comment;
    public ?Carbon $date;
    #[CastWith(CarbonCaster::class, format: 'm/d/Y')]
    public ?Carbon $date_with_format;
}
