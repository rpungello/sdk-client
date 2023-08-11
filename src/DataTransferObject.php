<?php

namespace Rpungello\SdkClient;

use Carbon\Carbon;
use DateTimeImmutable;
use Rpungello\SdkClient\Casters\CarbonCaster;
use Rpungello\SdkClient\Casters\DateTimeCaster;
use Spatie\DataTransferObject\Attributes\DefaultCast;

#[
    DefaultCast(DateTimeImmutable::class, DateTimeCaster::class),
    DefaultCast(Carbon::class, CarbonCaster::class),
]
class DataTransferObject extends \Spatie\DataTransferObject\DataTransferObject
{
}
