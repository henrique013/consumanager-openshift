<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 08/08/2016
 * Time: 16:25
 */

namespace App\Util\Handle;

use Slim\Http\Request;
use Slim\Http\Response;


trait GET
{
    public abstract function get(Request $request, Response $response);
}