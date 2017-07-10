<?php

// DIC configuration

$container = $app->getContainer();

// debugBar
$container['debugBar'] = new class()
{
  private $debugBar;

  function __construct()
  {
    $this->debugBar = new DebugBar\StandardDebugBar();
  }

  function __invoke()
  {
    return $this->debugBar;
  }
};


// monolog
$container['logger'] = function (\Interop\Container\ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// view renderer (twig)
$container['view'] = function (\Interop\Container\ContainerInterface $c) {
    $view = new \Slim\Views\Twig('../templates', [
        'cache' => '../cache'
    ]);
    // Instantiate and add Slim specific extension
    $basePath = \rtrim(\str_ireplace('index.php', '', $c->get('request')->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));

    // env not working
    //$c['debugBar']->addCollector(new DebugBar\Bridge\Twig\TwigCollector($view->getEnvironment()));
    return $view;
};

//mongodb
$container['mongo'] = new class($app->getContainer()['settings']['mongodb'])
{
  private $mongo;
  function __construct(array $settings)
  {
    $this->mongo = new \MongoBlog\DataAccess\MongoInteraction($settings['connection_string'], $settings['database']);
  }
  function __invoke(): \MongoBlog\DataAccess\MongoInteraction
  {
    return $this->mongo;
  }
};
