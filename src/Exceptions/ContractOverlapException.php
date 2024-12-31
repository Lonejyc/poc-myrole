<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ContractOverlapException extends ConflictHttpException
{
    public function __construct(string $message = 'The new contract period overlaps with an existing contract.')
    {
        parent::__construct($message);
    }
}
