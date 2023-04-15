<?php

namespace Api\Controllers;

use Api\Models\Ingredient;
use Api\Exceptions\ControllerException;

final class IngredientsController {
    private $_body;
    private string $_queryAction;
    private string $_queryParam;
    private array $_methodParams;
    
    public function __construct($endpointHandler)
    {
        $this->_body = $endpointHandler->getBody();
        $this->_queryAction = $endpointHandler->getQueryAction();
        $this->_queryParam = $endpointHandler->getQueryParam();
    }
    
    public function processIngredientRequest()
    {
        $ingredient = new Ingredient;
        
        if (!method_exists($ingredient, $this->_queryAction)) {
            throw new ControllerException('Method not found');
        }
        
        if ($this->_queryAction === 'selectIngredientById') {
            $id = intval($this->_queryParam);
            $ingredient->selectIngredientById($id);
        }
        elseif ($this->_queryAction === 'selectIngredientByName') {
            $ingredient->selectIngredientByName($this->_queryParam);
        }
        elseif ($this->_queryAction === 'selectIngredients') {
            $ingredient->selectIngredients();
        }
        elseif ($this->_queryAction === 'insertIngredient') {
            $ingredient->insertIngredient();
        }
        elseif ($this->_queryAction === 'updateIngredient') {
            $ingredient->updateIngredient();
        }
        else {
            $ingredient->deleteIngredient();
        }
    }
    }