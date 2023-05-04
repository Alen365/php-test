<?php

namespace App\Controllers;

use App\Collections\ArticleCollection;
use App\Database\Storage;
use App\Resources\ArticleResource;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ArticleController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $articlesData = Storage::get('articles');


        $articles = $articlesData->get();

        $response->getBody()->write(json_encode(ArticleResource::collect($articles)));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function find(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $articlesData = Storage::get('articles');

        $article = $articlesData->find($args['id']);

        if ($article->isEmpty()) {
            $response->getBody()->write(json_encode(['error' => 'Resource Not Found!']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $articleResource =  ArticleResource::make($article->first());
        $response->getBody()->write(json_encode($articleResource));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

}