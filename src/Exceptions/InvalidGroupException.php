<?php

namespace Sukristyan\LaravelMenuWrapper\Exceptions;

use InvalidArgumentException;

class InvalidGroupException extends InvalidArgumentException
{
    public static function create(string $given, array $expect)
    {
        $implode = implode(', ', $expect);
        return new static("The given role or permission should use guard `{$implode}` instead of `{$given}`.");
    }
}
