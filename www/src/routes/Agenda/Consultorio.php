<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 28/08/16
 * Time: 14:11
 */

namespace App\Route\Agenda;


use App\Util\Handle;
use App\Util\Handle\GET;
use DateTime;
use Slim\Http\Request;
use Slim\Http\Response;


class Consultorio extends Handle {

    use GET;


    function get(Request $request, Response $response) {

        $conID = $request->getAttribute('id');
        $data = $request->getAttribute('dt');


        /** @var \Twig_Environment $twig */
        $twig = $this->ci->get('twig');

        $context = $this->ci->get('settings')['Twig']['context']['sistema'];
        $context['data'] = new DateTime($data);
        $context['consultorio'] = [
            'id' => 10,
            'nome' => 'Brinquedoteca'
        ];
        $context['consultas'] = [
            [
                'horario' => '08:00',
                'resumo' => null
            ],
            [
                'horario' => '09:00',
                'resumo' => [
                    'paciente' => 'GUSTAVO IAN GIORI',
                    'aluno' => 'Pedro José',
                ]
            ],
            [
                'horario' => '10:00',
                'resumo' => null
            ],
            [
                'horario' => '11:00',
                'resumo' => null
            ],
            [
                'horario' => '12:00',
                'resumo' => [
                    'paciente' => 'Ronaldo Nazário de Lima',
                    'aluno' => 'Romário dos Santos',
                ]
            ]
        ];

        $view = $twig->render('agenda/consultorio/consultorio.twig', $context);

        $response->getBody()->write($view);


        return $response;
    }
}