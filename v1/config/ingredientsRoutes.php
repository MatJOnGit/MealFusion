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
        'action' => 'selectIngredientById',
        'uriFormat' => '/MealFusion/v1/ingredients?id={id}',
        'bodyTemplate' => NULL
    ],
    
    [
        'method' => 'GET',
        'query' => 'name',
        'permissions' => ['contributor', 'admin'],
        'action' => 'selectIngredientByName',
        'uriFormat' => '/MealFusion/v1/ingredients?name={name}',
        'bodyTemplate' => NULL
    ],
    
    [
        'method' => 'GET',
        'query' => '',
        'permissions' => ['contributor', 'admin'],
        'action' => 'selectIngredients',
        'uriFormat' => '/MealFusion/v1/ingredients',
        'bodyTemplate' => NULL
    ],
    
    /*************************************************************/
    /**********************  POST REQUESTS  **********************/
    
    [
        'method' => 'POST',
        'query' => '',
        'permissions' => ['contributor', 'admin'],
        'action' => 'insertIngredient',
        'uriFormat' => 'MealFusion/v1/ingredients',
        'bodyTemplate' => [
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
        'action' => 'updateIngredient',
        'uriFormat' => 'MealFusion/v1/ingredients?id={id}',
        'bodyTemplate' => [
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
        'bodyTemplate' => NULL
    ]
];