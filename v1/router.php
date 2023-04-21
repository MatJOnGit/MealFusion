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
    
    if (!$endpointHandler->isEndpointValid) {
        throw new EndpointException(401, 'Invalid request');
    }
    
    $resource = $endpointHandler->getResource();
    switch ($resource) {
        case 'ingredients':
            $ingredientsController = new IngredientsController($db, $endpointHandler);
            $ingredientsController->processIngredientRequest();
            break;
        case 'recipes':
            $recipesController = new RecipesController($db, $endpointHandler);
            $recipesController->processRecipeRequest();
            break;
        default:
            throw new EndpointException(500, 'Internal server error');
    }
}

catch (EndpointException $e) {
    $responseHandler = new ResponseHandler($e->getCode(), $e->getMessage());
}

catch (Exception $e) {
    $responseHandler = new ResponseHandler(500, 'Internal server error');
}