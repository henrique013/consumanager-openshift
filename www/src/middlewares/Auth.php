<?php

namespace App\Middleware;

use App\Util\Handle;
use App\Util\Handle\Middleware;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use RuntimeException;
use Slim\Http\Request;
use Slim\Http\Response;


class Auth extends Handle {

    use Middleware;


    public function __invoke(Request $request, Response $response, callable $next) {

        // verifica se a requisição deve passar pela autenticação de token

        /** @var \Slim\Http\Response $response */

        $settings = $this->ci->get('settings')['APP'];
        $hostServer = $url = $settings['server']['host'];
        $hostAPI = $url = $settings['api']['host'];

        $method = $request->getMethod();
        $uri = $request->getUri()->getHost() . $request->getUri()->getPath();

        // Exceções
        $cond1 = ($method === 'GET' && $uri === $hostServer . '/login');
        $cond2 = ($method === 'POST' && $uri === $hostAPI . '/auth/login');

        if ($cond1 || $cond2) {

            $response = $next($request, $response);

            return $response;
        }


        // verifica a autenticidade do token

        $cookies = $request->getCookieParams();

        if (!isset($cookies['token'])) {

            return $response->withRedirect('/login');
        }

        $token = $cookies['token'];
        $secret = $this->ci->get('settings')['JWT']['secret'];
        $sgner = new Sha256();

        try {

            $jwt = (new Parser())->parse($token);

            if (!$jwt->verify($sgner, $secret)) {

                throw new RuntimeException();
            }
        }
        catch (RuntimeException $e) {

            return $response->withStatus(403, 'Token invalid');
        }

        // adiciona o jwt no container de dependências
        $this->ci['JWT'] = $jwt;


        $response = $next($request, $response);

        return $response;
    }
}