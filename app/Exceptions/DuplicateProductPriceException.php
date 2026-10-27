<?php

namespace App\Exceptions;

use RuntimeException;

class DuplicateProductPriceException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'Ya existe un precio para este producto en la moneda indicada.'
        );
    }
}
