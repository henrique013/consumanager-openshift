<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Busca;


use App\Util\Handle;
use App\Util\Handle\GET;
use Slim\Http\Request;
use Slim\Http\Response;


class Pacientes extends Handle
{
    use GET;


    function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \PDO $conn */


        $pNome = $request->getParam('nome');
        $conn = $this->ci->get('PDO');


        if ($pNome)
        {
            $sql = "
                SELECT
                    p.id
                    ,p.num_prontuario
                    ,p.nome
                    ,p.telefone
                    ,to_char(p.dt_nasc, 'DD/MM/YYYY') AS dt_nascimento
                FROM tb_paciente p
                WHERE
                    p.nome ILIKE :nome
                ORDER BY
                    p.nome
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('nome', "%{$pNome}%");
        }
        else
        {
            $sql = "
                SELECT
                    p.id
                    ,p.num_prontuario
                    ,p.nome
                    ,p.telefone
                    ,to_char(p.dt_nasc, 'DD/MM/YYYY') AS dt_nascimento
                FROM tb_paciente p
                ORDER BY
                    p.nome
            ";
            $stmt = $conn->prepare($sql);
        }


        $stmt->execute();
        $context['pacientes'] = $stmt->fetchAll();
        $context['busca'] = $pNome;


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('busca/pacientes/pacientes.twig', $context);
        $response->write($view);


        return $response;
    }
}