<?php

namespace Rpungello\SdkClient\Tests\Dtos;

use DateTimeImmutable;
use Rpungello\SdkClient\DataTransferObject;

class DummyDto extends DataTransferObject
{
    public int $id;
    public string $name;
    public ?string $comment;
    public ?DateTimeImmutable $date;
}
