<?php

namespace Api\Handlers;

use Api\Models\User;

final class ApiRequestHandler {
    private $_authHeaderRegex;

    public function __construct() {
        $regex = require __DIR__ . '/../config/regex.php';
        $this->_authHeaderRegex = $regex['authKey'];
    }
    
    // Tests if the headers needed for a request are set and match the correct format
    public function handleHeaders(object $db, object $httpRequest): ?string {
        // Validates Content-type and Autorization headers
        $contentType = $httpRequest->getHeader('Content-Type') ?? false;
        $authHeader = $httpRequest->getHeader('Authentification') ?? false;
        
        if (!$contentType || $contentType !== 'application/json') {
            return false;
        }
        
        if (!$authHeader || !preg_match($this->_authHeaderRegex, $authHeader)) {
            return false;
        }
        
        // Extracts authentification key
        $authKey = substr($authHeader, 7);
        
        // Gets user rights from its authentification key
        return $this->_getUserRights($db, $authKey);
    }
    
    private function _getUserRights(object $db, string $authKey) {
        $userRight = new User;
        $userRights = $userRight->selectPermissions($db->connect(), $authKey);
        
        return ($userRights ? $userRights['api_permissions'] : null);
    }
}