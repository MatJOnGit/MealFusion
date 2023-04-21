<?php

namespace Api\Handlers;

final class ResponseHandler {
    private $_httpCode = 500;
    private $_alert = '';
    
    public function __construct($code, $alert = '')
    {
        $this->_httpCode = $code;
        $this->_alert = $alert;
        
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
            'message' => $this->_alert,
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
            'data' => $this->_alert
        ];
        
        http_response_code(200);
        header('Content-Type: application/json');
        
        echo json_encode($response);
    }
}