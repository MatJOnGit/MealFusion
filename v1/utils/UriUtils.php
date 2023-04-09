<?php

namespace Api\Utils;

use Api\Exceptions\EndpointException;
use Api\Handlers\ResponseHandler;
use Exception;

final class UriUtils {
    public bool $isUriValid = false;
    public string $query = '';
    public string $queryParam = '';
    public string $resource = '';
    
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
        return $this->query;
    }
    
    public function getQueryParam()
    {
        return $this->queryParam;
    }
    
    public function getResource()
    {
        return $this->resource;
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
                    throw new EndpointException('400');
                }
                
                $this->query = $this->_extractQuery();
                $this->queryParam = str_replace(['?id=', '?name='], '', $this->_strippedUri);
            }
            
            else {
                $this->isUriValid = true;
            }
        }
        
        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e);
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
        }
    }
    
    /************************************************************************************
    Remplaces underscores from strippedUri with spaces to form the query parameter to use
    in database, then checks if the type of data is correct depending on query value
    ************************************************************************************/
    private function _checkQueryParam()
    {
        try {
            if (!$this->isUriValid) {
                if (empty($this->queryParam)) {
                    throw new EndpointException('400');
                }
                
                if (!preg_match($this->_regexes['queryParam'], urldecode($this->_strippedUri))) {
                    throw new EndpointException('400');
                }
                
                $this->_strippedUri = str_replace('_', ' ', urldecode($this->_strippedUri));
                
                if (!$this->_checkQueryParamType()) {
                    throw new EndpointException('400');
                }
                
                $this->isUriValid = true;
            }
        }
        
        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e);
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
        }
    }
    
    /************************************************************************************
    Tests if the query parameter string is a "stringified" integer. If query value is set
    to "name", returns false if the query parameter contains only numbers. If the query
    value is set to "id", returns true if the query parameter contains only number.   
    ************************************************************************************/
    private function _checkQueryParamType()
    {
        if ($this->query === 'name') {
            return !preg_match($this->_regexes['onlyNumbers'], $this->queryParam);
        }
        
        if ($this->query === 'id') {
            return preg_match($this->_regexes['onlyNumbers'], $this->queryParam);
        }
        
        return false;
    }
    
    /*******************************************************************************************
    Extracts the resource from the temp strippedUrl formed in checkUri method and strips the uri
    from the resource string if there is any to split resource from the rest of the strippedUrl
    *******************************************************************************************/
    private function _checkResource()
    {
        try {
            if (empty($this->_strippedUri)) {
                throw new EndpointException('400');
            }
            
            if (!preg_match($this->_regexes['resource'], $this->_strippedUri, $matchedResource)) {
                throw new EndpointException('400');
            }
            
            $this->resource = $matchedResource[0];
            $this->_strippedUri = str_replace(['ingredients', 'recipes'], '', $this->_strippedUri);
        }
        
        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e);
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
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
                throw new EndpointException('400');
            }
            
            if (!preg_match($this->_regexes['uri'], $this->_uri)) {
                throw new EndpointException('400');
            }
            
            $this->_strippedUri = str_replace('/MealFusion/v1/', '', $this->_uri);
        }
        
        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e);
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
        }
    }
}