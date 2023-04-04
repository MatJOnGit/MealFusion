<?php

namespace Api\Tests;

class MethodTest {
    private $_allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];
    public $isAllowed;
    
    function __construct(string $method) {
        $this->isAllowed = in_array($method, $this->_allowedMethods);
    }
}