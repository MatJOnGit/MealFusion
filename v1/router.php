<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Api\Utils\DatabaseUtils;
use Api\Exceptions\EndpointException;
use Api\Controllers\IngredientsController;
use Api\Controllers\RecipesController;
use Api\Handlers\EndpointHandler;
use Api\Handlers\ResponseHandler;

try {
    $dbUtils = new DatabaseUtils();
    $db = $dbUtils->connect();
    
    $endpointHandler = new EndpointHandler($db);
    
    if ($endpointHandler->isEndpointValid) {
        $resource = $endpointHandler->getResource();
        
        if ($resource === 'ingredients') {
            $ingredientsController = new IngredientsController($endpointHandler);
            $responseHandler = new ResponseHandler($ingredientsController->processIngredientRequest());
        }
        
        else {
            $recipesController = new RecipesController($endpointHandler);
            $responseHandler = new ResponseHandler($recipesController->processRecipeRequest());
        }
    }
    
    // else {
    //     throw new EndpointException('400');
    // }
}
        
catch (EndpointException $e) {
    $responseHandler = new ResponseHandler($e);
    exit();
}

catch (Exception $e) {
    $responseHandler = new ResponseHandler('500');
    exit();
}