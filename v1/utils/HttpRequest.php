<?php

namespace Api\Utils;

final class HttpRequest {
    private $_method;
    private $_headers;
    private $_uri;
    
    public function __construct() {
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_headers = getallheaders();
        $this->_uri = $_SERVER['REQUEST_URI'];
    }
    
    public function getMethod() {
        return $this->_method;
    }
    
    public function getHeader($header) {
        return isset($this->_headers[$header]) ? $this->_headers[$header] : null;
    }
    
    public function getUri() {
        return $this->_uri;
    }
    
    public function getRequest() {
        return explode('/', $_SERVER['REQUEST_URI'])[2] ?? null;
    }
    
    public function getRequestParam() {
        return explode('/', $_SERVER['REQUEST_URI'])[3] ?? null;
    }
}