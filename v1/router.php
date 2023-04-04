<?php

declare(strict_types=1);
session_start();

class Request_Exception extends Exception {}

try {
    require_once __DIR__ . '/../vendor/autoload.php';
    
    $httpRequest = new Api\Utils\HttpRequest;
    $requestType = $httpRequest->getRequest();
    
    $methodTest = new Api\Tests\MethodTest($httpRequest->getMethod());
    $requestTest = new Api\Tests\RequestTest($requestType);
    
    if ($methodTest->isAllowed && $requestTest->isAllowed) {
        if (in_array($requestType, ['ingredient, ingredients'])) {
            echo "We've just received a " . $httpRequest->getMethod() . ' request on ingredients data';
        }
        
        elseif (in_array($requestType, ['recipe', 'recipes'])) {
            echo "We've just received a " . $httpRequest->getMethod() . ' request on recipes data';
        }
        
        else {
            throw new Request_Exception('Forgotten request type');
        }
    }
    
    else {
        echo 'On va rejeter la requête';
    }
}

catch(Request_Exception $e) {
    echo "New REQUEST EXCEPTION caught: '" . $e->getMessage() . "' in index file \non line " . $e->getLine();
}

catch(Exception $e) {
    echo "New exception caught: '" . $e->getMessage() . "' in index file \non line " . $e->getLine();
}