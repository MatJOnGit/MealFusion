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
        'query' => 'id',
        'permissions' => ['contributor', 'admin'],
        'action' => 'getIngredientById',
        'uriFormat' => '/MealFusion/v1/ingredients?id={id}',
        'bodyParamsFormat' => NULL
    ],

    [
        'method' => 'GET',
        'query' => 'name',
        'permissions' => ['contributor', 'admin'],
        'action' => 'getIngredientByName',
        'uriFormat' => '/MealFusion/v1/ingredients?name={name}',
        'bodyParamsFormat' => NULL
    ],

    [
        'method' => 'GET',
        'query' => '',
        'permissions' => ['contributor', 'admin'],
        'action' => 'getIngredients',
        'uriFormat' => '/MealFusion/v1/ingredients',
        'bodyParamsFormat' => NULL
    ],

    /*************************************************************/
    /**********************  POST REQUESTS  **********************/

    [
        'method' => 'POST',
        'query' => '',
        'permissions' => ['contributor', 'admin'],
        'action' => 'postIngredient',
        'uriFormat' => 'MealFusion/v1/ingredients',
        'bodyParamsFormat' => [
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
        'query' => 'id',
        'permissions' => ['contributor', 'admin'],
        'action' => 'editIngredient',
        'uriFormat' => 'MealFusion/v1/ingredients?id={id}',
        'bodyParamsFormat' => [
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
        'query' => 'id',
        'requiredParam' => true,
        'permissions' => ['admin'],
        'action' => 'deleteIngredient',
        'uriFormat' => 'MealFusion/v1/ingredients?id={id}',
        'bodyParamsFormat' => NULL
    ]
];