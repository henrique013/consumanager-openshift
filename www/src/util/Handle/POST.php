<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 08/08/2016
 * Time: 16:21
 */

namespace App\Util\Handle;

use Slim\Http\Request;
use Slim\Http\Response;


trait POST
{
    public abstract function post(Request $request, Response $response);
}