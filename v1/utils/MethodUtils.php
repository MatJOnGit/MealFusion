<?php

namespace Api\Utils;

use Api\Handlers\ResponseHandler;
use Api\Exceptions\EndpointException;
use Exception;

final class MethodUtils {
    public string $method;
    public bool $isMethodValid = false;
    
    private array $_validMethods = ['GET', 'POST', 'PUT', 'DELETE'];
    
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->_checkMethod();
    }
    
    /***********************************************************
    Extracts the method from the endpoint, then tests if the
    method is a valid method and set the result in isMethodValid
    ***********************************************************/
    private function _checkMethod()
    {
        try {
            if (!in_array($this->method, $this->_validMethods)) {
                throw new EndpointException('405');
            }
            
            $this->isMethodValid = true;
        }
        
        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e);
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
        }
    }
}