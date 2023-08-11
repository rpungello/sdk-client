<?php

namespace Rpungello\SdkClient\Casters;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Spatie\DataTransferObject\Caster;

class CarbonCaster implements Caster
{
    public function __construct(private array $types, private readonly ?string $format = null)
    {
    }

    public function cast(mixed $value): ?Carbon
    {
        try {
            if (! empty($this->format)) {
                return Carbon::createFromFormat($this->format, $value);
            } else {
                return Carbon::parse($value);
            }
        } catch (InvalidFormatException) {
            return null;
        }
    }
}
