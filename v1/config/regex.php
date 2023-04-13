<?php

return [
    'authKey' => '/^Bearer [a-z0-9]{40}$/i',
    'uri' => '/^\/MealFusion\/v1\/[\wéàèïï%\?=]{0,67}$/',
    'resource' => '/^(ingredients|recipes)/',
    'query' => '/^(\?id=|\?name=)/',
    'queryParam' => '/^[a-zA-Z0-9é\'-è_çàùëïüâêîû]{1,50}$/u',
    'onlyNumbers' => '/^\d+$/'
];