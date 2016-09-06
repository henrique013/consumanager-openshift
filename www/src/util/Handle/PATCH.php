<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 08/08/2016
 * Time: 16:28
 */

namespace App\Util\Handle;

use Slim\Http\Request;
use Slim\Http\Response;


trait PATCH
{
    public abstract function patch(Request $request, Response $response);
}