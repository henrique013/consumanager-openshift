<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 27/07/2016
 * Time: 14:51
 */

namespace App\Util;

use DateTime;
use Slim\Container;


abstract class Handle
{
    protected $ci;
    protected $context = [];


    public function __construct(Container $ci)
    {
        /** @var \Slim\Http\Request $request */


        $this->ci = $ci;
        $request = $this->ci->get('request');
        $path = $request->getUri()->getPath();


        if (!preg_match("/^\/ajax/", $path))
        {
            $this->context = [
                'title' => 'ConsuManager'
            ];


            if ($path !== '/auth/login')
            {
                // rotas especiais
                $aux = [];
                $data = preg_match("/^\/agenda\/(\d\d\d\d-\d\d-\d\d)/", $path, $aux) ? $aux[1] : date('Y-m-d');
                $this->context['data'] = new DateTime($data);
            }
        }
    }
}