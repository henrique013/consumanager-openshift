<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 08/08/2016
 * Time: 16:26
 */

namespace App\Util\Handle;

use Slim\Http\Request;
use Slim\Http\Response;


trait DELETE
{
    public abstract function delete(Request $request, Response $response);
}