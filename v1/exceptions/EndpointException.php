<?php

namespace Api\Exceptions;

use Exception;

class EndpointException extends Exception {
    public function __construct(int $httpCode, string $error = '') {
        parent::__construct($error, $httpCode);
    }
}