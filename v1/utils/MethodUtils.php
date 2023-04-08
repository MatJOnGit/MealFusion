<?php

namespace Api\Utils;

class MethodUtils {
    public array $validMethods = ['GET', 'POST', 'PUT', 'DELETE'];
    
    public string $method;
    
    public bool $isMethodValid;
    
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->validateMethod();
    }
    
    public function validateMethod ()
    {
        $this->isMethodValid = in_array($this->method, $this->validMethods);
    }
}