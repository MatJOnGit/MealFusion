<?php

namespace Api\Utils;

use Api\Exceptions\EndpointException;
use Api\Handlers\ResponseHandler;
use Exception;

final class UriUtils {
    private string $_uri;
    public string $resource = '';
    public string $query = '';
    public string $queryParam = '';
    
    private array $_regexes;
    
    public string $strippedUri = '';
    public bool $isUriValid = false;
    
    public function __construct()
    {
        $this->_regexes = include('./v1/config/regex.php');
        $this->_uri = $_SERVER['REQUEST_URI'];
        
        $this->checkUri();
        $this->checkResource();
        $this->checkQuery();
        $this->checkQueryParam();
    }
    
    /*********************************************************
    Strips the url from the domain + version string in the uri
    *********************************************************/
    private function checkUri()
    {
        try {
            if (empty($this->_uri)) {
                throw new EndpointException('400');
            }
            
            if (!preg_match($this->_regexes['uri'], $this->_uri)) {
                throw new EndpointException('400');
            }
            
            $this->strippedUri = str_replace('/MealFusion/v1/', '', $this->_uri);
        }
        
        catch (EndpointException $e) {
            echo 'Uri error';
            $responseHandler = new ResponseHandler($e);
            exit();
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
            exit();
        }
    }
    
    /***********************************************
    Extract the resource out of the uri, then trips
    the url from the resource string if there is any
    ***********************************************/
    private function checkResource ()
    {
        try {
            if (empty($this->strippedUri)) {
                throw new EndpointException('400');
            }
            
            if (!preg_match($this->_regexes['resource'], $this->strippedUri, $matchedResource)) {
                throw new EndpointException('400');
            }
            
            $this->resource = $matchedResource[0];
            $this->strippedUri = str_replace(['ingredients', 'recipes'], '', $this->strippedUri);
        }
        
        catch (EndpointException $e) {
            echo 'Resource error';
            $responseHandler = new ResponseHandler($e);
            exit();
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
            exit();
        }
    }
    
    /**********************************************
    Extracts the query out of the uri, then strips
    the uri from the query string (if there is one)
    **********************************************/
    private function checkQuery ()
    {
        try {
            if (!empty($this->strippedUri)) {
                if (!preg_match($this->_regexes['query'], $this->strippedUri)) {
                    throw new EndpointException('400');
                }
                
                $this->query = $this->extractQuery();
                $this->queryParam = str_replace(['?id=', '?name='], '', $this->strippedUri);
            }
            
            else {
                $this->isUriValid = true;
            }
        }
        
        catch (EndpointException $e) {
            echo 'Query error';
            $responseHandler = new ResponseHandler($e);
            exit();
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
            exit();
        }
    }
    
    /*********************************************************
    Extracts the query parameter out of the uri, then tests it
    with a method to know it the query has a attended format
    *********************************************************/
    private function checkQueryParam ()
    {
        try {
            if (!$this->isUriValid) {
                if (empty($this->queryParam)) {
                    throw new EndpointException('400');
                }
                
                if (!preg_match($this->_regexes['queryParam'], urldecode($this->strippedUri))) {
                    throw new EndpointException('400');
                }
                
                $this->strippedUri = str_replace('_', ' ', urldecode($this->strippedUri));
                
                if ($this->validateQueryParamType()) {
                    $this->isUriValid = true;
                }
            }
        }
        
        catch (EndpointException $e) {
            echo 'Query param error';
            $responseHandler = new ResponseHandler($e);
            exit();
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
            exit();
        }
    }
    
    /************************************************************************
    Returns the query contained between the first "?" and "=" met, in the uri
    ************************************************************************/
    private function extractQuery ()
    {
        $strippedUri = $this->strippedUri;
        $questionMarkPosition = strpos($strippedUri, "?") + 1;
        $queryLength = strpos($strippedUri, "=") - $questionMarkPosition;
        $query = substr($strippedUri, $questionMarkPosition, $queryLength);
        
        return $query;
    }
    
    /************************************************************************
    Verifies that the query parameter is of the attended type :
        - an "id" query must be followed by a string with letters,
        - a "name" query must be followed by a string containing only letters
    ************************************************************************/
    public function validateQueryParamType()
    {
        if ($this->query === 'name') {
            return !preg_match($this->_regexes['onlyNumbers'], $this->queryParam);
        }
        
        if ($this->query === 'id') {
            return preg_match($this->_regexes['onlyNumbers'], $this->queryParam);
        }
        
        return false;
    }
}