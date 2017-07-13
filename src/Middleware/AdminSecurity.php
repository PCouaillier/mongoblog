<?php

namespace MongoBlog\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Class AdminSecurity
 * @package MongoBlog\Middleware
 */
final class AdminSecurity
{

    const STORE_NAME = 'User\Rights[admin]';

    private $session;

    public function __construct($session)
    {
      $this->session = $session;
    }

    /**
     *  User\Rights[admin]
     */
    public static function promoteAdmin()
    {
        $session[self::STORE_NAME] = true;
    }

    /**
     *  User\Rights[admin]
     */
    public function demoteAdmin()
    {
        $session[self::STORE_NAME] = false;
    }

    private function isAdmin()
    {
        return (bool)($this->session[self::STORE_NAME]);
    }

    private static function setRefusedPage(Response $response)
    {
        $refusedResponse = $response->withStatus(403, 'Not Admin')
            ->withHeader('Content-type','text/html');
        $refusedResponse
            ->getBody()
            ->write('<!DOCTYPE html><html><head><title>Not Allowed</title><head><body>WTF</body></html>');
        return $refusedResponse;
    }

    /**
     * Don't execute $next if not admin (use session User\Rights[admin])
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request,Response $response, $next)
    {
        $isAdmin = $this->isAdmin();
        $request->withAttribute('isAdmin', $isAdmin);
        return ($isAdmin)? $next($request, $response) : $this->setRefusedPage($response);
    }
}
