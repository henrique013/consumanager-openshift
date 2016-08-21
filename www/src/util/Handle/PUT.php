<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 08/08/2016
 * Time: 16:17
 */

namespace App\Util\Handle;

use Slim\Http\Request;
use Slim\Http\Response;


trait PUT
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    abstract function put(Request $request, Response $response);
}