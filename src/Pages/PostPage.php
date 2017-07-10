<?php

namespace MongoBlog\Pages;

use \MongoBlog\DataAccess\MongoInteraction;
use \MongoBlog\DataAccess\Entities\Post;
use \Slim\Container;
use \Slim\Http\Request;
use \Slim\Http\Response;

/**
 *
 */
final class PostPage
{
    /** @var MongoInteraction */
    private $mongo;

    /**
     * PostPage constructor.
     * @param App $app
     * @internal param MongoInteraction $mongo
     */
    function __construct(Container $container)
    {
        $this->mongo = $container->get('mongo');
    }

    /**
     * @param $keywords
     * @return array
     */
    private static function parseKeywords($keywords): array
    {
        return $keywords? json_decode($keywords, true) : [];
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    function __invoke(Request $request, Response $response)
    {
        $author = $request->getParam('user');
        $content = $request->getParam('content');
        if (!$request || !$response) return $response->withStatus(400);

        $keywords = self::parseKeywords($request->getParam('keywords'));
        $this->mongo->addPost(
            Post::publish($author, $content, $keywords)
        );
        return $response->withStatus(204);
    }
}
