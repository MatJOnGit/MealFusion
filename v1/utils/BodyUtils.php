<?php

namespace Api\Utils;

use Api\Handlers\ResponseHandler;
use Api\Exceptions\EndpointException;
use JsonException;
use Exception;

class BodyUtils {
    private $_body;
    private string $_method;
    private string $_queryAction = '';
    private array $_requiredBodyMethods = ['POST', 'PUT'];
    private array $_routes;
    
    public bool $areDeeperBodyTestsRequired = true;
    public bool $isBodyStructureValid = false;
    private bool $_isBodyEmpty = true;
    private bool $_isBodyRequired;
    private $_bodyTemplate;
    
    public bool $isBodyValid = false;
    
    public function __construct(string $method, string $resource, $query)
    {
        $this->_method = $method;
        $this->_body = $this->_getBodySettings();
        $this->_isBodyRequired = $this->_checkBodyRequirement();
        $this->_checkTestingNeeds();
        $this->_getBodyTemplate($resource, $query);
    }
    
    /*************************************************************************
    Verifies if the body has every parameters need to run the request properly
    *************************************************************************/
    public function checkBodyContent()
    {
        $this->_checkKeys($this->_bodyTemplate, $this->_body);
        $this->_checkValuesType($this->_bodyTemplate, $this->_body);
        $this->_checkIngredientsParamsContent($this->_bodyTemplate, $this->_body);
    }
    
    public function getBody()
    {
        return $this->_body;
    }

    public function getRoutes()
    {
        return $this->_routes;
    }
    
    public function getQueryAction()
    {
        return $this->_queryAction;
    }
    
    /****************************************************************************
    Returns true if the request has a POST or PUT method, otherwise returns false
    ****************************************************************************/
    private function _checkBodyRequirement()
    {
        if (in_array($this->_method, $this->_requiredBodyMethods)) {
            return true;
        }
        
        return false;
    }
    
    // /********************************************************************
    // Set the values of areDeeperBodyTestsRequired and isBodyStructureValid
    // based on body completion and the intended body format.
    // ********************************************************************/
    private function _checkTestingNeeds()
    {
        if ($this->_isBodyRequired && $this->_isBodyEmpty) {
            throw new EndpointException(400, 'Bad request');
        }
        
        if (!$this->_isBodyRequired && !$this->_isBodyEmpty) {
            throw new EndpointException(400, 'Bad request');
        }
        
        if (!$this->_isBodyRequired && $this->_isBodyEmpty) {
            $this->isBodyStructureValid = true;
            $this->areDeeperBodyTestsRequired = false;
        }
    }
    
    /*****************************************************************
    If the body contains JSON formated data, sets isBodyEmpty to false
    and returns them as an array. Otherwise, simply returns NULL
    *****************************************************************/
    private function _getBodySettings()
    {
        try {
            $stringifiedBody = file_get_contents("php://input");
            if ($stringifiedBody === '') {
                return NULL;
            }
            
            $body = json_decode($stringifiedBody, true, 512, JSON_THROW_ON_ERROR);
            
            if (!$body || count($body) === 0) {
                new ResponseHandler(400, 'Bad request');
            }
            
            $this->_isBodyEmpty = false;
            return $body;
        }
        
        catch (JsonException $e) {
            $responseHandler = new ResponseHandler($e);
        }
        
        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e);
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler(500, 'Internal server error');
        }
    }
    
    /*****************************************
    Returns the appropriate resource file data
    *****************************************/
    private function _getBodyTemplate(string $resource, string $query = '')
    {
        $this->_routes = include('./v1/config/' . $resource . 'Routes.php');
        
        foreach ($this->_routes as $route) {
            if ($route['method'] === $this->_method && $route['query'] === $query) {
                $this->_queryAction = $route['action'];
                $this->_bodyTemplate = $route['bodyTemplate'];
                return [];
            }
        }
        
        throw new EndpointException(500, 'Internal server error');
    }
    
    /*******************************************************************
    Verifies, if an 'ingredients' key exists and does contain some keys,
    that ingredients are unique, then verifies each ingredient data
    *******************************************************************/
    private function _checkIngredientsParamsContent($template, $bodyElt)
    {
        try {
            if (array_key_exists('ingredients', $bodyElt)) {
                $ingredientParamCount = count(array_keys($template['ingredients']));
                
                if ($ingredientParamCount === 0) {
                    throw new EndpointException(400, 'Bad request');
                }
                
                $ingredientTemplate = $template['ingredients'][0];
                
                foreach ($bodyElt['ingredients'] as $ingredientIndex => $ingredientData) {
                    $this->_checkKeys($ingredientTemplate, $bodyElt['ingredients'][$ingredientIndex]);
                    $this->_checkValuesType($ingredientTemplate, $bodyElt['ingredients'][$ingredientIndex]);
                }
            }
            
            $this->isBodyValid = true;
        }

        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e->getCode(), $e->getMessage());
            exit();
        }
        
        catch (Exception $e) {
            var_dump($e->getCode());
            $responseHandler = new ResponseHandler(500, 'Internal server error');
            exit();
        }
    }
    
    /********************************************************************************
    Verifies that every needed keys in the request matching template is set in entry,
    then if every key in the user entry is set in the request matching template
    ********************************************************************************/
    private function _checkKeys($template, $body)
    {
        foreach ($body as $bodyKey => $bodyValue) {
            if (!array_key_exists($bodyKey, $template)) {
                throw new EndpointException(400, 'Bad request');
            }
        }
        
        foreach ($template as $templateKey => $templateValue) {
            if (!array_key_exists($templateKey, $body)) {
                throw new EndpointException(400, 'Bad request');
            }
        }
        
        if (count($body) !== count(array_unique(array_keys($body)))) {
            throw new EndpointException(400, 'Bad request');
        }
    }
    
    /******************************************************
    Verifies that every needed keys in the request matching
    template contains the needed type of data.
    ******************************************************/
    private function _checkValuesType($template, $body)
    {
        try {
            foreach ($body as $bodyKey => $bodyValue) {
                $isStringKey = is_string($body[$bodyKey]);
                $isNullKey = is_null($body[$bodyKey]);
                $isIntKey = is_int($body[$bodyKey]);
                $isArrayKey = is_array($body[$bodyKey]);
                
                if ($template[$bodyKey] === 'string' && !$isStringKey) {
                    throw new EndpointException(400, 'Bad request');
                }
                
                if ($template[$bodyKey] === '?string' && !$isNullKey && !$isStringKey) {
                    throw new EndpointException(400, 'Bad request');
                }
                
                if ($template[$bodyKey] === 'int' && !$isIntKey) {
                    throw new EndpointException(400, 'Bad request');
                }
                
                if (is_array($template[$bodyKey]) && !$isArrayKey) {
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
}