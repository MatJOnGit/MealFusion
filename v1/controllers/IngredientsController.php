<?php

namespace Api\Controllers;

use Api\Models\Ingredient;
use Api\Exceptions\ControllerException;
use Api\Handlers\ResponseHandler;
use Exception;

final class IngredientsController {
    private $_body;
    private string $_queryAction;
    private string $_queryParam;

    private object $_db;
    
    public function __construct(object $db, object $endpointHandler)
    {
        $this->_body = $endpointHandler->getBody();
        $this->_queryAction = $endpointHandler->getQueryAction();
        $this->_queryParam = $endpointHandler->getQueryParam();
        $this->_db = $db;
    }
    
    public function processIngredientRequest()
    {
        try {
            $ingredient = new Ingredient;
        
            $ingredientId = intval($this->_queryParam);
            
            switch ($this->_queryAction) {
                case 'selectIngredientById':
                    $response = $ingredient->selectIngredientById($this->_db, $ingredientId);
                    break;
                    
                case 'selectIngredientsByName':
                    $response = $ingredient->selectIngredientsByName($this->_db, $this->_queryParam);
                    break;
                    
                case 'selectIngredients':
                    $response = $ingredient->selectIngredients($this->_db);
                    break;
                    
                case 'insertIngredient':
                    $response = $ingredient->insertIngredient($this->_db, $this->_body);
                    break;
                    
                case 'updateIngredient':
                    $response = $ingredient->updateIngredient($this->_db, $ingredientId, $this->_body);
                    break;
                    
                case 'deleteIngredient':
                    $response = $ingredient->deleteIngredient($this->_db, $ingredientId);
                    break;
                    
                default:
                    throw new ControllerException(500, 'Internal server error');
            }
            
            $responseHandler = new ResponseHandler(200, $response);
        }
        
        catch (ControllerException $e) {
            $responseHandler = new ResponseHandler($e->getCode(), $e->getMessage());
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler(500, 'Internal server error');
        }
    }
}