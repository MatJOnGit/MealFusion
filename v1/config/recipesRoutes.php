<?php

/***********************************************************
Returns the info of each type of api request for recipes:
    - the header method to access the request
    - the uri needed to access the requests,
    - the permissions required for the request to succeed,
    - the method of the corresponding model to call,
    - the different parameters to include in the body,
    along with their needed type of data value
***********************************************************/
return [
    [

    /****************************************************/
    /******************  GET REQUESTS  ******************/

        'method' => 'GET',
        'uri' => '/MealFusion/v1/recipes?id={id}',
        'permissions' => ['guest', 'contributor', 'admin'],
        'action' => 'getRecipeById',
        'bodyParams' => []
    ],

    [
        'method' => 'GET',
        'uri' => '/MealFusion/v1/recipes?name={name}',
        'permissions' => ['contributor', 'admin'],
        'action' => 'getRecipeByName',
        'bodyParams' => []
    ],

    [
        'method' => 'GET',
        'uri' => '/MealFusion/v1/recipes',
        'permissions' => ['contributor', 'admin'],
        'action' => 'getRecipes',
        'bodyParams' => []
    ],

    /*************************************************************/
    /**********************  POST REQUESTS  **********************/

    [
        'method' => 'POST',
        'uri' => 'MealFusion/v1/recipes',
        'permissions' => ['contributor', 'admin'],
        'action' => 'postRecipe',
        'bodyParams' => [
            'name' => 'string',
            'ingredients' => [
                'id' => 'int',
                'quantity' => 'int'
            ]
            /* Add other table elements if you want to add multiple 
            ingredients to this recipe */
        ]
    ],

    /************************************************************/
    /**********************  PUT REQUESTS  **********************/

    [
        'method' => 'PUT',
        'uri' => 'MealFusion/v1/recipe?id={id}',
        'permissions' => ['contributor', 'admin'],
        'action' => 'editRecipe',
        'bodyParams' => [
            'name' => 'string',
            'ingredients' => [
                'id' => 'int',
                'quantity' => 'int'
            ]
            /* Add other table elements if you want to add multiple 
            ingredients to this recipe */
        ]
    ],

    /***************************************************************/
    /**********************  DELETE REQUESTS  **********************/

    [
        'method' => 'DELETE',
        'uri' => 'MealFusion/v1/recipes?id={id}',
        'permissions' => ['admin'],
        'action' => 'deleteRecipe',
        'bodyParams' => []
    ]
];