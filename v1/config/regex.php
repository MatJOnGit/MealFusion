<?php

return [
    'authKey' => '/^Bearer [a-z0-9]{40}$/i',
    'uri' => '/^\/MealFusion\/v1\/[\wéàèïï%\?=]{0,47}$/',
    'resource' => '/^(ingredients|recipes)/',
    'query' => '/^(\?id=|\?name=)/',
    'queryParam' => '/^[a-zA-Z0-9é\'-è_çàùëïüâêîû]{1,30}$/u',
    'onlyNumbers' => '/^\d+$/'
];