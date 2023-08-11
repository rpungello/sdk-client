<?php

namespace Rpungello\SdkClient\Tests\Dtos;

use DateTimeImmutable;
use Rpungello\SdkClient\Casters\DateTimeCaster;
use Rpungello\SdkClient\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;

class DummyDtoDateFormat extends DataTransferObject
{
    public int $id;
    public string $name;
    public ?string $comment;
    #[CastWith(DateTimeCaster::class, format: 'm/d/Y')]
    public ?DateTimeImmutable $date;
}
