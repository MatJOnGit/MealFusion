<?php

namespace Api\Utils;

use Api\Handlers\ResponseHandler;
use Api\Exceptions\EndpointException;
use Exception;

final class MethodUtils {
    private string $_method;
    public bool $isMethodValid = false;
    
    private array $_validMethods = ['GET', 'POST', 'PUT', 'DELETE'];
    
    public function __construct()
    {
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_checkMethod();
    }

    public function getMethod() {
        return $this->_method;
    }
    
    /***********************************************************
    Extracts the method from the endpoint, then tests if the
    method is a valid method and set the result in isMethodValid
    ***********************************************************/
    private function _checkMethod()
    {
        try {
            if (!in_array($this->_method, $this->_validMethods)) {
                throw new EndpointException(405, 'Unauthorized method');
            }
            
            $this->isMethodValid = true;
        }

        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e->getCode(), $e->getMessage());
            exit();
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler(500, 'Internal server error');
            exit();
        }
    }
}