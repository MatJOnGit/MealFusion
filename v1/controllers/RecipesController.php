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
        
        $recipeId = intval($this->_queryParam);
        $response = NULL;
        
        switch ($this->_queryAction) {
            case 'selectRecipeById':
                $response = $recipe->selectRecipeById($this->_db, $recipeId);
                break;

            case 'selectRecipesByName':
                $response = $recipe->selectRecipesByName($this->_db, $this->_queryParam);
                break;

            case 'selectRecipes':
                $response = $recipe->selectRecipes($this->_db);
                break;

            case 'insertRecipe':
                $response = $recipe->insertRecipe($this->_db, $this->_body);
                break;

            case 'updateRecipe':
                $response = $recipe->updateRecipe($this->_db, $this->_body);
                break;

            case 'deleteRecipe':
                $response = $recipe->deleteRecipe($this->_db, $recipeId);
                break;

            default:
                throw new ControllerException('Method not found');
                break;
        }

        var_dump($response);
    }
}