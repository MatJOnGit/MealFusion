<?php

namespace Api\Handlers;

use Api\Models\User;

final class ApiRequestHandler {
    private $_authHeaderRegex = '/^Bearer [a-z0-9]{40}$/i';
    
    // Tests if the headers needed for a request are set and match the correct format
    public function getKeyOwnerRights(object $db, object $httpRequest): ?string {
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
        return $this->getUserRights($db, $authKey);
    }
    
    public function getUserRights(object $db, string $authKey) {
        $userRight = new User;
        $userRights = $userRight->selectPermissions($db->connect(), $authKey);
        
        return ($userRights ? $userRights['api_permissions'] : null);
    }
}