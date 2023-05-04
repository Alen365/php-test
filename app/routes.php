<?php

use App\Controllers\ArticleController;
use App\Resources\ArticleResource;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** @noinspection PhpUndefinedVariableInspection */
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");

    return $response;
});

//$app->get('/articles', function (Request $request, Response $response, $args) {

//
//
//    return $response;
//});
$app->get('/articles', [ArticleController::class, 'index']);
$app->get('/articles/{id}', [ArticleController::class, 'find']);