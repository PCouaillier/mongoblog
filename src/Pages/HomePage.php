<?php
namespace MongoBlog\Pages;

use \DebugBar\DebugBar;
use MongoBlog\DataAccess\MongoInteraction;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container;
use Slim\Views\Twig;

/**
 * Class HomePage
 * @package MongoBlog\Pages
 */
final class HomePage
{
    /** @var DebugBar $debugBar */
    private $debugBar;

    /** @var MongoInteraction $mongo*/
    private $mongo;

    /** @var Twig $view*/
    private $view;


    /**
     * HomePage constructor.
     * @param Container $container
     */
    function __construct(Container $container)
    {
        $this->debugBar = $container->get('debugBar');
        $this->mongo = $container->get('mongo');
        $this->view = $container->get('view');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    function __invoke(Request $request, Response $response): Response
    {
        try {
            $articles = $this->mongo->fetchPost();
        }
        catch(\Exception $e)
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->debugBar->getCollector('exceptions')->addException($e);
        }
        return $this->view->render($response, 'index.twig', ['articles'=>$articles??[],'debugBar'=>$this->debugBar->getJavascriptRenderer()]);
    }
}
