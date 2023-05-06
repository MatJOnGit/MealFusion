<?php

namespace Api\Handlers;

final class ResponseHandler {
    private $_httpCode;
    private $_response = '';
    
    public function __construct($code, $response)
    {
        $this->_httpCode = $code;
        $this->_response = $response;
        
        switch ($this->_httpCode) {
            case '200':
                $this->_sendResponse();
                break;
            default:
                $this->_sendError();
                exit();
        }
    }
    
    private function _sendError()
    {
        $error = [
            'status' => $this->_httpCode,
            'message' => $this->_response,
            'documentation' => 'http://localhost:8080/MealFusion/documentation.html'
        ];
        
        http_response_code($this->_httpCode);
        header('Content-Type: application/json');
        
        echo json_encode($error);
    }
    
    private function _sendResponse()
    {
        $response = [
            'status' => $this->_httpCode,
            'data' => $this->_response
        ];
        
        http_response_code(200);
        header('Content-Type: application/json');
        
        echo json_encode($response);
    }
}