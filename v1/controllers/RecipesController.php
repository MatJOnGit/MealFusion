<?php

namespace Api\Controllers;

use Api\Models\Recipe;
use Api\Exceptions\ControllerException;

final class RecipesController {
    private $_body;
    private string $_queryAction;
    private string $_queryParam;
    private array $_methodParams;

    private object $_db;
    
    public function __construct(object $db, object $endpointHandler)
    {
        $this->_body = $endpointHandler->getBody();
        $this->_queryAction = $endpointHandler->getQueryAction();
        $this->_queryParam = $endpointHandler->getQueryParam();
        $this->_db = $db;
    }
    
    public function processRecipeRequest()
    {
        $recipe = new Recipe;
        
        if (!method_exists($recipe, $this->_queryAction)) {
            throw new ControllerException('Method not found');
        }
        
        if ($this->_queryAction === 'selectRecipeById') {
            $recipe->{$this->_queryAction}();
        }
        elseif ($this->_queryAction === 'selectRecipesByName') {
            $recipe->{$this->_queryAction}();
        }
        elseif ($this->_queryAction === 'selectRecipes') {
            $recipe->{$this->_queryAction}();
        }
        elseif ($this->_queryAction === 'addRecipe') {
            $recipe->{$this->_queryAction}();
        }
        elseif ($this->_queryAction === 'updateRecipe') {
            $recipe->{$this->_queryAction}();
        }
        else {
            $recipe->{$this->_queryAction}();
        }
    }
}