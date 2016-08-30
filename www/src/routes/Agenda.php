<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route;


use App\Util\Handle;
use App\Util\Handle\GET;
use DateTime;
use Slim\Http\Request;
use Slim\Http\Response;


class Agenda extends Handle {

    use GET;


    function get(Request $request, Response $response) {

        $data = $request->getAttribute('dt', new DateTime());


        /** @var \Twig_Environment $twig */
        $twig = $this->ci->get('twig');

        $context = $this->ci->get('settings')['Twig']['context']['sistema'];
        $context['data'] = $data;
        $context['resumos'] = [
            [
                'consultorio' => [
                    'id' => 10,
                    'nome' => 'Brinquedoteca'
                ],
                'horarios' => [
                    'total' => rand(8, 12),
                    'ocupados' => rand(0, 8)
                ]
            ],
            [
                'consultorio' => [
                    'id' => 20,
                    'nome' => 'Consultório 1'
                ],
                'horarios' => [
                    'total' => rand(8, 12),
                    'ocupados' => rand(0, 8)
                ]
            ],
            [
                'consultorio' => [
                    'id' => 30,
                    'nome' => 'Consultório 2'
                ],
                'horarios' => [
                    'total' => rand(8, 12),
                    'ocupados' => rand(0, 8)
                ]
            ],
            [
                'consultorio' => [
                    'id' => 40,
                    'nome' => 'Consultório 3'
                ],
                'horarios' => [
                    'total' => rand(8, 12),
                    'ocupados' => rand(0, 8)
                ]
            ],
            [
                'consultorio' => [
                    'id' => 50,
                    'nome' => 'Consultório 4'
                ],
                'horarios' => [
                    'total' => rand(8, 12),
                    'ocupados' => rand(0, 8)
                ]
            ]
        ];

        $view = $twig->render('agenda/agenda.twig', $context);

        $response->getBody()->write($view);


        return $response;
    }
}