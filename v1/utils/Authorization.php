<?php

namespace Api\Utils;

final class Authorization {
    private $_validUsers = [
        'user' => [
            'GET' => [
                'recipe' => ['getRecipeById']
            ]
        ],
        'contributor' => [
            'GET' => [
                'ingredient' => ['getIngredientById', 'getIngredientsByName', 'getIngredients'],
                'recipe' => ['getRecipeById', 'getRecipesByName', 'getRecipes']
            ]
        ],
        'admin' => [
            'GET' => [
                'ingredient' => ['getIngredientById', 'getIngredientsByName', 'getIngredients'],
                'recipe' => ['getRecipeById', 'getRecipesByName', 'getRecipes']
            ]
        ]
    ];
}