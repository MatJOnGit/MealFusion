<?php

namespace Api\Utils;

use Api\Exceptions\EndpointException;
use Api\Handlers\ResponseHandler;
use Exception;

final class UriUtils {
    public bool $isUriValid = false;

    private string $_query = '';
    private string $_queryParam = '';
    private string $_resource = '';
    private array $_regexes;
    private string $_strippedUri = '';
    private string $_uri;
    
    public function __construct()
    {
        $this->_regexes = include('./v1/config/regex.php');
        $this->_uri = $_SERVER['REQUEST_URI'];

        $this->_checkUri();
        $this->_checkResource();
        $this->_checkQuery();
        $this->_checkQueryParam();
    }
    
    public function getQuery()
    {
        return $this->_query;
    }
    
    public function getQueryParam()
    {
        return $this->_queryParam;
    }
    
    public function getResource()
    {
        return $this->_resource;
    }
    
    /************************************************************************
    Returns the query contained between the first "?" and "=" met, in the uri
    ************************************************************************/
    private function _extractQuery()
    {
        $strippedUri = $this->_strippedUri;
        $questionMarkPosition = strpos($strippedUri, "?") + 1;
        $queryLength = strpos($strippedUri, "=") - $questionMarkPosition;
        $query = substr($strippedUri, $questionMarkPosition, $queryLength);
        
        return $query;
    }
    
    /*******************************************************************************************
    Extracts the query from the temp strippedUrl formed in checkResource method and strips the
    uri from the query string if there is any to split resource from the rest of the strippedUrl
    *******************************************************************************************/
    private function _checkQuery()
    {
        try {
            if (!empty($this->_strippedUri)) {
                if (!preg_match($this->_regexes['query'], $this->_strippedUri)) {
                    throw new EndpointException(400, 'Bad request');
                }
                
                $this->_query = $this->_extractQuery();
                $this->_queryParam = str_replace(['?id=', '?name='], '', $this->_strippedUri);
            }
            
            else {
                $this->isUriValid = true;
            }
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
    
    /************************************************************************************
    Replaces underscores from strippedUri with spaces to form the query parameter to use
    in database, then checks if the type of data is correct depending on query value
    ************************************************************************************/
    private function _checkQueryParam()
    {
        try {
            if (!$this->isUriValid) {
                if (empty($this->_queryParam)) {
                    throw new EndpointException(400, 'Bad request');
                }
                
                if (!preg_match($this->_regexes['queryParam'], urldecode($this->_strippedUri))) {
                    throw new EndpointException(400, 'Bad request');
                }
                
                $this->_queryParam = str_replace('_', ' ', $this->_queryParam);
                
                if ($this->_queryParam !== '') {
                    $this->_checkQueryParamType();
                }
                
                $this->isUriValid = true;
            }
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
    
    /************************************************************************************
    Tests if the query parameter string is a "stringified" integer. If query value is set
    to "name", returns false if the query parameter contains only numbers. If the query
    value is set to "id", returns true if the query parameter contains only number.   
    ************************************************************************************/
    private function _checkQueryParamType()
    {
        try {
            if ($this->_query === 'name') {
                if (preg_match($this->_regexes['onlyNumbers'], $this->_queryParam)) {
                    throw new EndpointException(400, 'Bad request');
                }
            }
            
            if ($this->_query === 'id') {
                if (!preg_match($this->_regexes['onlyNumbers'], $this->_queryParam)) {
                    throw new EndpointException(400, 'Bad request');
                }
            }
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
    
    /*******************************************************************************************
    Extracts the resource from the temp strippedUrl formed in checkUri method and strips the uri
    from the resource string if there is any to split resource from the rest of the strippedUrl
    *******************************************************************************************/
    private function _checkResource()
    {
        try {
            if (empty($this->_strippedUri)) {
                throw new EndpointException(400, 'Bad request');
            }
            
            if (!preg_match($this->_regexes['resource'], $this->_strippedUri, $matchedResource)) {
                throw new EndpointException(400, 'Bad request');
            }
            
            $this->_resource = $matchedResource[0];
            $this->_strippedUri = str_replace(['ingredients', 'recipes'], '', $this->_strippedUri);
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
    
    /****************************************************************************************
    Strips the url from the domain + version string in the uri if the uri in not empty and if
    the start of the uri matches what is intended, then sets the temp results in strippedUri
    ****************************************************************************************/
    private function _checkUri()
    {
        try {
            if (empty($this->_uri)) {
                throw new EndpointException(400, 'Bad request');
            }
            
            if (!preg_match($this->_regexes['uri'], $this->_uri)) {
                throw new EndpointException(400, 'Bad request');
            }
            
            $this->_strippedUri = str_replace('/MealFusion/v1/', '', $this->_uri);
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