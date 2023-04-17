<?php

namespace Api\Utils;

use Api\Models\User;
use Api\Handlers\ResponseHandler;
use Api\Exceptions\EndpointException;
use Exception;

final class HeadersUtils {
    public string $authKey;
    public string $permissions = '';
    public bool $areHeadersValid = false;
    
    private array $_regexes;
    
    public function __construct($db)
    {
        $this->_regexes = include('./v1/config/regex.php');
        
        $this->_checkHeaders();
        $this->_checkKeyPermissions($db);
    }
    
    public function getAuthKey()
    {
        return $this->authKey;
    }
    
    public function getPermissions()
    {
        return $this->permissions;
    }
    
    /*************************************************************************
    Extracts the needed headers then tests them if they have a adequate format
    *************************************************************************/
    private function _checkHeaders()
    {
        try {
            $allHeaders = getallheaders();
            $contentType = array_key_exists('Content-Type', $allHeaders) ? $allHeaders['Content-Type'] : false;
            $authHeader = array_key_exists('Authorization', $allHeaders) ? $allHeaders['Authorization'] : false;
            
            if (!$contentType || $contentType !== 'application/json') {
                throw new EndpointException('400');
            }
            
            if (!$authHeader || !preg_match($this->_regexes['authKey'], $authHeader)) {
                throw new EndpointException('400');
            }
            
            $this->authKey = str_replace("Bearer ", "", $allHeaders['Authorization']);
        }
        
        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e);
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
        }
    }
    
    /*****************************************************************
    Verifies a authentification key in database if it is not empty,
    then saves the returned key owner's permissions if any is returned
    *****************************************************************/
    private function _checkKeyPermissions($db)
    {
        try {
            if (!$this->authKey) {
                throw new EndpointException('401');
            }
            
            if (!$db) {
                throw new EndpointException('500');
            }
            
            $user = new User;
            
            $permissions = $user->selectPermissions($db, $this->authKey);
            if (!$permissions) {
                throw new EndpointException('401');
            }
            
            $this->permissions = $permissions['api_permissions'];
            $this->areHeadersValid = true;
        }
        
        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e);
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
        }
    }
}