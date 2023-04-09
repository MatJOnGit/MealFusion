<?php

namespace Api\Exceptions;

use Exception;

class EndpointException extends Exception {
    public string $error;

    public function __construct(string $error = '') {
        $this->getError($error);
    }

    private function getError($error) {
        if ($error) {
            echo 'New Endpoint exception caught for a ' . $error . ' error.';
        }
    }
}