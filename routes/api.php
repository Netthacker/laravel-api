<?php

/**
 * Authenticate with JWT
 */
$this->post('auth', 'Auth\AuthApiController@authenticate');
$this->post('auth-refresh', 'Auth\AuthApiController@refreshToken');
$this->get('me', 'Auth\AuthApiController@getAuthenticatedUser');

/**
 * Versioning API
 */
 $this->group([
     'prefix' => 'v1', 
     'namespace' => 'API', 
     'middleware' => 'auth:api',
    ], function(){

/**
 * Category
 */

$this->apiResource('categories', 'CategoryController'); 

/**
 * Products
 */

$this->apiResource('products', 'ProductController');

/**
 * Products in a category
 */

$this->get('categories/{id}/products', 'CategoryController@products');

 });