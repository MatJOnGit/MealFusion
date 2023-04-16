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
        'action' => 'selectRecipeById',
        'uriFormat' => '/MealFusion/v1/recipes?id={id}',
        'bodyTemplate' => NULL
    ],
    
    [
        'method' => 'GET',
        'query' => 'name',
        'permissions' => ['contributor', 'admin'],
        'action' => 'selectRecipesByName',
        'uriFormat' => '/MealFusion/v1/recipes?name={name}',
        'bodyTemplate' => NULL
    ],
    
    [
        'method' => 'GET',
        'query' => '',
        'permissions' => ['contributor', 'admin'],
        'action' => 'selectRecipes',
        'uriFormat' => '/MealFusion/v1/recipes',
        'bodyTemplate' => NULL
    ],
    
    /*************************************************************/
    /**********************  POST REQUESTS  **********************/
    
    [
        'method' => 'POST',
        'query' => '',
        'permissions' => ['contributor', 'admin'],
        'action' => 'insertRecipe',
        'uriFormat' => 'MealFusion/v1/recipes',
        'bodyTemplate' => [
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
        'action' => 'updateRecipe',
        'uriFormat' => 'MealFusion/v1/recipe?id={id}',
        'bodyTemplate' => [
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
        'bodyTemplate' => NULL
    ]
];