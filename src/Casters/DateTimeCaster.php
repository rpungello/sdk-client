<?php

namespace Rpungello\SdkClient\Casters;

use DateTimeImmutable;
use Spatie\DataTransferObject\Caster;

class DateTimeCaster implements Caster
{
    public function __construct(private array $types = [], private readonly string $format = 'Y-m-d')
    {
    }

    public function cast(mixed $value): ?DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat($this->format, $value) ?: null;
    }
}
