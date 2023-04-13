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

    /****************************************************/
    /******************  GET REQUESTS  ******************/
    
    [
        'method' => 'GET',
        'query' => 'id',
        'permissions' => ['guest', 'contributor', 'admin'],
        'action' => 'getRecipeById',
        'uriFormat' => '/MealFusion/v1/recipes?id={id}',
        'bodyParamsFormat' => NULL
    ],
    
    [
        'method' => 'GET',
        'query' => 'name',
        'permissions' => ['contributor', 'admin'],
        'action' => 'getRecipeByName',
        'uriFormat' => '/MealFusion/v1/recipes?name={name}',
        'bodyParamsFormat' => NULL
    ],
    
    [
        'method' => 'GET',
        'query' => '',
        'permissions' => ['contributor', 'admin'],
        'action' => 'getRecipes',
        'uriFormat' => '/MealFusion/v1/recipes',
        'bodyParamsFormat' => NULL
    ],
    
    /*************************************************************/
    /**********************  POST REQUESTS  **********************/
    
    [
        'method' => 'POST',
        'query' => '',
        'permissions' => ['contributor', 'admin'],
        'action' => 'postRecipe',
        'uriFormat' => 'MealFusion/v1/recipes',
        'bodyParamsFormat' => [
            'name' => 'string',
            'ingredients' => [
                '0' => [
                    'id' => 'int',
                    'quantity' => 'int'
                ]
                /* Add other table elements if you want to add multiple 
                ingredients to this recipe */
            ]
        ]
    ],
    
    /************************************************************/
    /**********************  PUT REQUESTS  **********************/
    
    [
        'method' => 'PUT',
        'query' => 'id',
        'permissions' => ['contributor', 'admin'],
        'action' => 'editRecipe',
        'uriFormat' => 'MealFusion/v1/recipe?id={id}',
        'bodyParamsFormat' => [
            'name' => 'string',
            'ingredients' => [
                '0' => [
                    'id' => 'int',
                    'quantity' => 'int'
                ]
                /* Add other table elements if you want to add multiple 
                ingredients to this recipe */
            ]
        ]
    ],
    
    /***************************************************************/
    /**********************  DELETE REQUESTS  **********************/
    
    [
        'method' => 'DELETE',
        'query' => 'id',
        'requiredParam' => true,
        'permissions' => ['admin'],
        'action' => 'deleteRecipe',
        'uriFormat' => 'MealFusion/v1/recipes?id={id}',
        'bodyParamsFormat' => NULL
    ]
];