<?php

namespace Api\MOdels;

use PDO;

final class Ingredient {
    public function selectIngredientById(int $id)
    {
        echo 'On est bien dans la méthode selectIngredientById de la classe Ingredient. ';
        echo "L'id à trouver en database est " . $id;
    }
    
    public function selectIngredientByName(string $name)
    {
        echo 'On est bien dans la méthode selectIngredientByName de la classe Ingredient. ';
        echo "Le nom à trouver en database est " . $name;
    }
    
    public function selectIngredients()
    {
        echo 'On est bien dans la méthode selectIngredients de la classe Ingredient';
    }
    
    public function insertIngredient()
    {
        echo 'On est bien dans la méthode insertIngredient de la classe Ingredient';
    }
    
    public function updateIngredient()
    {
        echo 'On est bien dans la méthode updateIngredient de la classe Ingredient';
    }
    
    public function deleteIngredient()
    {
        echo 'On est bien dans la méthode deleteIngredient de la classe Ingredient';
    }
}