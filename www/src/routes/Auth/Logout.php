<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 29/07/2016
 * Time: 14:01
 */

namespace App\Route\Auth;

use App\Util\Handle;
use App\Util\Handle\GET;
use Slim\Http\Request;
use Slim\Http\Response;


class Logout extends Handle
{
    use GET;


    public function get(Request $request, Response $response)
    {
        setcookie('AUTH_TOKEN', null, -1, '/');

        $response = $response->withRedirect('/auth/login');

        return $response;
    }
}