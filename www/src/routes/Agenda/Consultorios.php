<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Agenda;


use App\Util\Handle;
use App\Util\Handle\GET;
use Slim\Http\Request;
use Slim\Http\Response;


class Consultorios extends Handle {

    use GET;


    function get(Request $request, Response $response) {

        $data = $request->getAttribute('data', date('Y-m-d'));


        /** @var \Twig_Environment $twig */
        $twig = $this->ci->get('twig');

        $context = [
            'resumos' => [
                [
                    'consultorio' => [
                        'nome' => 'Brinquedoteca'
                    ],
                    'horarios' => [
                        'total' => rand(8, 12),
                        'ocupados' => rand(0, 8)
                    ]
                ],
                [
                    'consultorio' => [
                        'nome' => 'Consultório 1'
                    ],
                    'horarios' => [
                        'total' => rand(8, 12),
                        'ocupados' => rand(0, 8)
                    ]
                ],
                [
                    'consultorio' => [
                        'nome' => 'Consultório 2'
                    ],
                    'horarios' => [
                        'total' => rand(8, 12),
                        'ocupados' => rand(0, 8)
                    ]
                ],
                [
                    'consultorio' => [
                        'nome' => 'Consultório 3'
                    ],
                    'horarios' => [
                        'total' => rand(8, 12),
                        'ocupados' => rand(0, 8)
                    ]
                ],
                [
                    'consultorio' => [
                        'nome' => 'Consultório 4'
                    ],
                    'horarios' => [
                        'total' => rand(8, 12),
                        'ocupados' => rand(0, 8)
                    ]
                ]
            ]
        ];

        $view = $twig->render('agenda/consultorios.twig', $context);

        $response->getBody()->write($view);


        return $response;
    }
}