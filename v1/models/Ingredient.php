<?php

namespace Api\MOdels;

use Api\Handlers\ResponseHandler;
use PDOException;
use PDO;

final class Ingredient {
    public function selectIngredientById(object $db, int $ingredientId)
    {
        $selectIngredientQuery = 
            'SELECT
                ingr.id, ingr.name, ingr.preparation, ingr.type, ingr.measure, nut.calories, nut.proteins, nut.carbs, nut.sodium, nut.fibers, nut.sugar, nut.data_source
            FROM ingredients ingr
            INNER JOIN nutrients nut ON ingr.id = nut.ingredient_id
            WHERE ingr.id = ?';
        $selectIngredientStatement = $db->prepare($selectIngredientQuery);
        $selectIngredientStatement->execute([$ingredientId]);
        
        return $selectIngredientStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectIngredientsByName(object $db, string $ingredientName)
    {
        $selectIngredientsQuery = 
            'SELECT
                ingr.id, ingr.name, ingr.preparation, ingr.type, ingr.measure, nut.calories, nut.proteins, nut.carbs, nut.sodium, nut.fibers, nut.sugar, nut.data_source
            FROM ingredients ingr
            INNER JOIN nutrients nut ON ingr.id = nut.ingredient_id
            WHERE ingr.name LIKE CONCAT('%', ? '%')';
        $selectIngredientsStatement = $db->prepare($selectIngredientsQuery);
        $selectIngredientsStatement->execute(["%$ingredientName%"]);
        
        return $selectIngredientsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectIngredients(object $db)
    {
        $selectAllIngredientsQuery = 
            'SELECT
                ingr.id, ingr.name, ingr.preparation, ingr.type, ingr.measure, nut.calories, nut.proteins, nut.carbs, nut.sodium, nut.fibers, nut.sugar, nut.data_source
            FROM ingredients ingr
            INNER JOIN nutrients nut ON ingr.id = nut.ingredient_id';
        $selectAllIngredientsStatement = $db->prepare($selectAllIngredientsQuery);
        $selectAllIngredientsStatement->execute();
        
        return $selectAllIngredientsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function insertIngredient(object $db, array $ingredientParams)
    {
        try {
            $db->beginTransaction();
            
            $insertIngredientQuery =
                'INSERT INTO ingredients (name, preparation, type, measure) VALUES (:name, :preparation, :type, :measure)';
            $insertIngredientStatement = $db->prepare($insertIngredientQuery);
            $insertIngredientStatement->execute([
                'name' => $ingredientParams['name'],
                'preparation' =>  $ingredientParams['preparation'],
                'type' =>  $ingredientParams['type'],
                'measure' =>  $ingredientParams['measure']
            ]);
            
            $insertIngredientNutrientsQuery =
                'INSERT INTO nutrients (id, calories, fat, proteins, carbs, sodium, fibers, sugar, data_source) VALUES (:id, :calories, :fat, :proteins, :carbs, :sodium, :fibers, :sugar, :source)';
            $insertIngredientNutrientsStatement = $db->prepare($insertIngredientNutrientsQuery);
            $insertIngredientNutrientsStatement->execute([
                'id' => $db->lastInsertId(),
                'calories' => $ingredientParams['calories'],
                'fat' => $ingredientParams['fat'],
                'proteins' => $ingredientParams['proteins'],
                'carbs' => $ingredientParams['carbs'],
                'sodium' => $ingredientParams['sodium'],
                'fibers' => $ingredientParams['fibers'],
                'sugar' => $ingredientParams['sugar'],
                'source' => $ingredientParams['note']
            ]);
            
            $db->commit();
        }
        
        catch (PDOException $e) {
            $db->rollBack();
            $responseHandler = new ResponseHandler('500');
        }
    }
    
    public function updateIngredient(object $db, int $ingredientId, array $ingredientParams)
    {
        try {
            $db->beginTransaction();
            
            $updateIngredientQuery =
                'UPDATE ingredients SET name = :name, preparation = :preparation, type = :type, measure = :measure WHERE id = :id';
            $updateIngredientStatement = $db->prepare($updateIngredientQuery);
            $updateIngredientStatement->execute([
                'name' => $ingredientParams['name'],
                'preparation' =>  $ingredientParams['preparation'],
                'type' =>  $ingredientParams['type'],
                'measure' =>  $ingredientParams['measure'],
                'id' => $ingredientId
            ]);
            
            $updateIngredientNutrientsQuery =
                'UPDATE nutrients SET calories = :calories, fat = :fat, proteins = :proteins, carbs = :carbs, sodium = :sodium, fibers = :fibers, sugar = :sugar, data_source = :source WHERE id = :id';
            $updateIngredientNutrientsStatement = $db->prepare($updateIngredientNutrientsQuery);
            $updateIngredientNutrientsStatement->execute([
                'calories' => $ingredientParams['calories'],
                'fat' => $ingredientParams['fat'],
                'proteins' => $ingredientParams['proteins'],
                'carbs' => $ingredientParams['carbs'],
                'sodium' => $ingredientParams['sodium'],
                'fibers' => $ingredientParams['fibers'],
                'sugar' => $ingredientParams['sugar'],
                'source' => $ingredientParams['note'],
                'id' => $ingredientId
            ]);
            
            $db->commit();
        }
        
        catch (PDOException $e) {
            $db->rollBack();
            $responseHandler = new ResponseHandler('500');
        }
    }
    
    public function deleteIngredient(object $db, int $ingredientId)
    {
        try {
            $db->beginTransaction();
            
            $deleteNutrientsQuery = 'DELETE FROM nutrients WHERE id = ?';
            $deleteNutrientsStatement = $db->prepare($deleteNutrientsQuery);
            $deleteNutrientsStatement->execute([$ingredientId]);
            
            $deleteIngredientQuery = 'DELETE FROM ingredients WHERE id = ?';
            $deleteIngredientStatement = $db->prepare($deleteIngredientQuery);
            $deleteIngredientStatement->execute([$ingredientId]);
            
            $db->commit();
        }
        
        catch (PDOException $e) {
            $db->rollBack();
            $responseHandler = new ResponseHandler('500');
        }
    }
}