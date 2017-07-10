<?php

namespace MongoBlog\Pages;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \MongoBlog\Middleware\AdminSecurity;

/**
 *
 */
final class ConnectPage
{
  function __invoke(Request $request, Response $response): Response
  {
    $body = $request->getParsedBody();
    $username = trim($body->get('username'));
    $password = trim($body->get('password'));

    if ($username === 'root' && $password === 'root') {
      (new AdminSecurity($_SESSION))->promoteAdmin();
      return $response->withStatus(204);
    }
    return $response->withStatus(403);
  }
}
