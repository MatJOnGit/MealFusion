<?php

namespace Api\Tests;

class RequestTest {
    private $_allowedRequests = ['ingredient', 'ingredients', 'recipe', 'recipes'];
    public $isAllowed;
    
    function __construct(string $request) {
        $this->isAllowed = in_array($request, $this->_allowedRequests);
    }
}