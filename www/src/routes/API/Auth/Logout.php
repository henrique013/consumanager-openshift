<?php

namespace App\Route\API\Auth;

use App\Util\Handle;
use App\Util\Handle\GET;
use Slim\Http\Request;
use Slim\Http\Response;


class Logout extends Handle {

    use GET;


    public function get(Request $request, Response $response) {

        setcookie('token', null, -1, '/', null, null, true);


        return $response;
    }
}