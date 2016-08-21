<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 28/07/2016
 * Time: 16:05
 */

namespace App\Util\Handle;

use App\Util\Handle;
use Slim\Http\Request;
use Slim\Http\Response;


trait Middleware
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param callable $next
     * @return \Slim\Http\Response
     */
    abstract public function __invoke(Request $request, Response $response, callable $next);
}