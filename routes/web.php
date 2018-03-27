<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$recipe = \App\Utilities\RecipeData::getRecipe();

$router->get('/', function () use ($router) {
    return 'Recipe API';
});

$router->group(['prefix' => 'v1/api'], function () use ($router) {

    $router->get('recipes', ['uses' => 'RecipeController@showAll']);

    $router->get('recipes/{id}', ['uses' => 'RecipeController@showOneRecipe']);

    $router->get('recipes/cuisine/{cuisine}', ['uses' => 'RecipeController@showCuisineRecipe']);

    $router->post('recipes/rate/{id}', ['uses' => 'RecipeController@rateRecipe']);

    $router->post('recipes/create', ['uses' => 'RecipeController@createRecipe']);

    $router->put('recipes/{id}', ['uses' => 'RecipeController@updateRecipe']);
});