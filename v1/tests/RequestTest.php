<?php

namespace Api\Tests;

class RequestTest {
    private $_allowedRequests = ['ingredients', 'recipes'];
    public $isAllowed;
    
    function __construct(string $request) {
        $this->isAllowed = in_array($request, $this->_allowedRequests);
    }
}