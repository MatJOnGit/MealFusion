<?php

/***************************************************************
Returns the info of each type of api request for ingredients:
    - the header method to access the request,
    - the uri needed to access the requests,
    - the permissions required for the request to succeed,
    - the method of the corresponding model to call,
    - the different parameters to include in the body,
    along with their needed type of data value
***************************************************************/

return [

    /****************************************************/
    /******************  GET REQUESTS  ******************/
    
    [
        'method' => 'GET',
        'uri' => '/MealFusion/v1/ingredients?id={id}',
        'permissions' => ['contributor', 'admin'],
        'action' => 'getIngredientById',
        'bodyParams' => []
    ],

    [
        'method' => 'GET',
        'uri' => '/MealFusion/v1/ingredients?name={name}',
        'permissions' => ['contributor', 'admin'],
        'action' => 'getIngredientByName',
        'bodyParams' => []
    ],

    [
        'method' => 'GET',
        'uri' => '/MealFusion/v1/ingredients',
        'permissions' => ['contributor', 'admin'],
        'action' => 'getIngredients',
        'bodyParams' => []
    ],

    /*************************************************************/
    /**********************  POST REQUESTS  **********************/

    [
        'method' => 'POST',
        'uri' => 'MealFusion/v1/ingredients',
        'permissions' => ['contributor', 'admin'],
        'action' => 'postIngredient',
        'bodyParams' => [
            'name' => 'string',
            'preparation' => '?string',
            'type' => 'string',
            'measure' => '?string',
            'calories' => 'int',
            'fat' => 'int',
            'proteins' => 'int',
            'carbs' => 'int',
            'sodium' => 'int',
            'fibers' => 'int',
            'sugar' => 'int',
            'note' => 'string'
        ]
    ],
    
    /************************************************************/
    /**********************  PUT REQUESTS  **********************/

    [
        'method' => 'PUT',
        'uri' => 'MealFusion/v1/ingredients?id={id}',
        'permissions' => ['contributor', 'admin'],
        'action' => 'editIngredient',
        'bodyParams' => [
            'name' => 'string',
            'preparation' => '?string',
            'type' => 'string',
            'measure' => '?string',
            'calories' => 'int',
            'fat' => 'int',
            'proteins' => 'int',
            'carbs' => 'int',
            'sodium' => 'int',
            'fibers' => 'int',
            'sugar' => 'int',
            'note' => 'string'
        ]
    ],

    /***************************************************************/
    /**********************  DELETE REQUESTS  **********************/

    [
        'method' => 'DELETE',
        'uri' => 'MealFusion/v1/ingredients?id={id}',
        'permissions' => ['admin'],
        'action' => 'deleteIngredient',
        'bodyParams' => []
    ]
];