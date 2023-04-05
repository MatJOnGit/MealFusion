<?php

namespace Api\Controllers;

use Api\Handlers\ApiRequestHandler;
use Api\Utils\Database;

abstract class CollectionController {
    public function handleRequest(object $httpRequest) {
        $db = new Database;
        $headerChecker = new ApiRequestHandler();
        
        $keyOwnerRights = $headerChecker->handleHeaders($db, $httpRequest);
        
        if ($keyOwnerRights) {
            echo "Key owner " . substr($httpRequest->getHeader('Authentification'), 7) . " is a " . $keyOwnerRights;
        }
        
        else {
            echo "Invalid api key";
        }
    }
}