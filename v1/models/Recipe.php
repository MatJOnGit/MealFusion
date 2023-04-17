<?php

namespace Api\Models;

use Api\Handlers\ResponseHandler;
use RuntimeException;
use Exception;
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
            "SELECT
                recipe_id, name, COUNT(recipe_id) as ingredientsCount
            FROM recipes 
            WHERE name LIKE CONCAT('%', ?, '%')
            GROUP BY recipe_id, name";
        $selectRecipesStatement = $db->prepare($selectRecipesQuery);
        $selectRecipesStatement->execute([$recipeName]);
        
        return $selectRecipesStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectRecipes(object $db)
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
    
    /*********************************************************************
    Verifies that a recipe id exists, then updates this recipes ingredient
    id and its quantity with new values set in the request body
    *********************************************************************/
    public function insertNewRecipe(object $db, array $recipeParams)
    {
        $recipeId = $db->query('SELECT MAX(recipe_id) AS max_id FROM recipes')->fetchColumn();
        $recipeId = $recipeId !== false ? $recipeId + 1 : 1;
        
        if (!$recipeId) {
            throw new Exception("Unable to generate recipe ID");
        }
        
        $insertRecipeQuery = 'INSERT INTO recipes (recipe_id, name, ingredient_id, ingredient_quantity) VALUES (:recipe_id, :name, :ingredient_id, :ingredient_quantity)';
        $insertRecipeStatement = $db->prepare($insertRecipeQuery);
        
        $db->beginTransaction();
        
        try {
            foreach ($recipeParams['ingredients'] as $ingredient) {
                $insertRecipeStatement->execute([
                    ':recipe_id' => $recipeId,
                    ':name' => $recipeParams['name'],
                    ':ingredient_id' => $ingredient['id'],
                    ':ingredient_quantity' => $ingredient['quantity']
                ]);
            }
            
            $db->commit();
        }
        
        catch (Exception $e) {
            $db->rollBack();
            $responseHandler = new ResponseHandler('500');
        }
    }
    
    /*********************************************************************
    Verifies that a recipe id exists, then updates this recipes ingredient
    id and its quantity with new values set in the request body
    *********************************************************************/
    public function updateRecipeIngredient(object $db, int $recipeId, array $recipeParams)
    {
        $db->beginTransaction();
        
        try {
            $checkRecipeQuery = 'SELECT COUNT(*) FROM recipes WHERE recipe_id = :recipeId';
            $checkRecipeStatement = $db->prepare($checkRecipeQuery);
            $checkRecipeStatement->execute(['recipeId' => $recipeId]);
            $recipeExists = $checkRecipeStatement->fetchColumn();
            
            if ($recipeExists === 0) {
                throw new RuntimeException("La recette n'existe pas dans la base de données");
            }
            
            $updateRecipeIngredientQuery = 'UPDATE recipes SET ingredient_id = :new_id, ingredient_quantity = :new_quantity WHERE recipe_id = :recipe_id AND ingredient_id = :former_id';
            $updateRecipeIngredientStatement = $db->prepare($updateRecipeIngredientQuery);
            $updateRecipeIngredientStatement->execute([
                'recipe_id' => $recipeId,
                'former_id' => $recipeParams['formerId'],
                'new_id' => $recipeParams[0]['newId'],
                'new_quantity' => $recipeParams[0]['newQuantity']
            ]);
            
            if ($updateRecipeIngredientStatement->rowCount() === 0) {
                throw new RuntimeException("Impossible de modifier l'ingrédient de la recette");
            }
            
            $db->commit();
        }
        
        catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
    
    /************************************************************
    Verifies that a recipe id is not included in food_plan table,
    then delete all references of that recipe id in recipe table 
    ************************************************************/
    public function deleteRecipe(object $db, int $recipeId)
    {
        $checkFoodPlanQuery = 'SELECT COUNT(*) FROM food_plans WHERE recipe_id = ?';
        $checkFoodPlanStatement = $db->prepare($checkFoodPlanQuery);
        $checkFoodPlanStatement->execute([$recipeId]);
        
        if ($checkFoodPlanStatement->fetchColumn() > 0) {
            throw new RuntimeException("Impossible de supprimer la recette car elle est présente dans un plan nutritionnel.");
        }
        
        $deleteRecipeQuery = 'DELETE FROM recipes WHERE recipe_id = ?';
        
        $deleteRecipeStatement = $db->prepare($deleteRecipeQuery);
        $deleteRecipeStatement->execute([$recipeId]);
        
        if ($deleteRecipeStatement->rowCount() === 0) {
            throw new RuntimeException("Impossible de supprimer la recette.");
        }
    }
}