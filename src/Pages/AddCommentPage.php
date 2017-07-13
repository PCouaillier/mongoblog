<?php
/**
 * Created by PhpStorm.
 * User: paulcouaillier
 * Date: 13/07/2017
 * Time: 11:04
 */

namespace Pages;

use MongoBlog\DataAccess\Entities\Comment;
use MongoBlog\DataAccess\MongoInteraction;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AddCommentPage
 * @package Pages
 */
final class AddCommentPage
{
    /** @var MongoInteraction $mongo */
    private $mongo;

    /**
     * AddCommentPage constructor.
     * @param Container $container
     */
    function __construct(Container $container)
    {
        $this->mongo = $container->get('mongo');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    function __invoke(Request $request, Response $response)
    {
        $postId = $request->getParam('postId');
        $author = $request->getParam('author');
        $content = $request->getParam('content');

        if (!$postId || !$author || !$content) return $response->withStatus(400);

        $this->mongo->addComment($postId, Comment::publish($author, $content));
        return $response->withStatus(204);
    }
}