<?php

namespace Api\Models;

use PDO;

final class Recipe {
    public function selectRecipeById()
    {
        echo 'On est bien dans la méthode selectRecipeById de la classe Recipe';
    }
    
    public function selectRecipeByName()
    {
        echo 'On est bien dans la méthode selectRecipeByName de la classe Recipe';
    }
    
    public function selectRecipes()
    {
        echo 'On est bien dans la méthode selectRecipes de la classe Recipe';
    }
    
    public function insertRecipe()
    {
        echo 'On est bien dans la méthode insertRecipe de la classe Recipe';
    }
    
    public function updateRecipe()
    {
        echo 'On est bien dans la méthode updateRecipe de la classe Recipe';
    }
    
    public function deleteRecipe()
    {
        echo 'On est bien dans la méthode deleteRecipe de la classe Recipe';
    }
}