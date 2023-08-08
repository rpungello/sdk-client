<?php

namespace Rpungello\SdkClient\Tests\Dtos;

use Spatie\DataTransferObject\DataTransferObject;

class DummyDto extends DataTransferObject
{
    public int $id;
    public string $name;
    public ?string $comment;
}
