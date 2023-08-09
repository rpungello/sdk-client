<?php

namespace Rpungello\SdkClient\Tests\Dtos;

use DateTimeImmutable;
use Rpungello\SdkClient\Casters\DateTimeCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class DummyDtoDateFormat extends DataTransferObject
{
    public int $id;
    public string $name;
    public ?string $comment;
    #[CastWith(DateTimeCaster::class, format: 'm/d/Y')]
    public ?DateTimeImmutable $date;
}
