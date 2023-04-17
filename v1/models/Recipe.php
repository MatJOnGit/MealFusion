<?php

namespace Api\Models;

use Api\Handlers\ResponseHandler;
use PDOException;
use PDO;

final class Recipe {
    public function selectRecipeById(object $db, int $recipeId)
    {
        $selectRecipeQuery = 
            'SELECT
                recipe_id, name, COUNT(recipe_id) AS ingredientsCount 
            FROM recipes 
            WHERE recipe_id = ? 
            GROUP BY recipe_id, name';
        $selectRecipeStatement = $db->prepare($selectRecipeQuery);
        $selectRecipeStatement->execute([$recipeId]);
        
        return $selectRecipeStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectRecipesByName(object $db, string $recipeName)
    {
        $selectRecipesQuery =
            'SELECT
                recipe_id, name, COUNT(recipe_id) as ingredientsCount
            FROM recipes 
            WHERE name LIKE CONCAT('%', ? '%')
            GROUP BY name';
        $selectRecipesStatement = $db->prepare($selectRecipesQuery);
        $selectRecipesStatement->execute([$recipeName]);
        
        return $selectRecipesStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectRecipes($db)
    {
        $selectAllRecipesQuery =
            'SELECT
                recipe_id, name, count(recipe_id) as ingredientsCount
            FROM recipes
            GROUP BY recipe_id, name';
        $selectAllRecipesStatement = $db->prepare($selectAllRecipesQuery);
        $selectAllRecipesStatement->execute();
        
        return $selectAllRecipesStatement->fetchAll(PDO::FETCH_ASSOC);
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