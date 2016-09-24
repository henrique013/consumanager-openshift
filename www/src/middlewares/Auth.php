<?php

namespace App\Middleware;

use App\Util\Handle;
use App\Util\Handle\Middleware;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Slim\Http\Request;
use Slim\Http\Response;


class Auth extends Handle
{
    use Middleware;


    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var \Slim\Http\Response $response */
        /** @var \Lcobucci\JWT\Token $token */


        $path = $request->getUri()->getPath();


        if ($path === '/auth/login') // exceções
        {
            return $next($request, $response);
        }


        // vaida o token
        $settings = $this->ci->get('settings');
        $signer = new Sha256();
        $token = $this->ci->get('jwt_token');
        if (!$token || !$token->verify($signer, $settings['JWT']['secret']))
        {
            return $response->withRedirect('/auth/login');
        }


        return $next($request, $response);
    }
}