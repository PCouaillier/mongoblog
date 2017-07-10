<?php
namespace MongoBlog\Pages;

use \DebugBar\DebugBar;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container;

/**
 *
 */
final class HomePage
{
  private $container;

  function __construct(Container $container)
  {
    $this->container = $container;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @return Response
   */
  function __invoke(Request $request, Response $response): Response
  {
    /** @var $debugBar DebugBar */
    $debugBar = $this->container->get('debugBar');
    try {
      $articles = $this->container->get('mongo')->fetchPost();
    }
    catch(\Exception $e)
    {
      /** @noinspection PhpUndefinedMethodInspection */
      $debugBar->getCollector('exceptions')->addException($e);
    }
    return $this->container->get('view')->render($response, 'index.twig', ['articles'=>$articles??[],'debugBar'=>$debugBar->getJavascriptRenderer()]);
  }
}
