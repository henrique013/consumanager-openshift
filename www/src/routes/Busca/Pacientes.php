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
                    p.ID AS id
                    ,p.NOME AS nome
                    ,p.TELEFONE AS telefone
                    ,DATE_FORMAT(p.DT_NASC, '%d/%m/%Y') AS dt_nascimento
                FROM TB_PACIENTE p
                WHERE
                    p.NOME LIKE :NOME
                ORDER BY
                    p.NOME
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('NOME', "%{$pNome}%");
        }
        else
        {
            $sql = "
                SELECT
                    p.ID AS id
                    ,p.NOME AS nome
                    ,p.TELEFONE AS telefone
                    ,DATE_FORMAT(p.DT_NASC, '%d/%m/%Y') AS dt_nascimento
                FROM TB_PACIENTE p
                ORDER BY
                    p.NOME
            ";
            $stmt = $conn->prepare($sql);
        }


        $stmt->execute();
        $context['pacientes'] = $stmt->fetchAll();


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('busca/pacientes/pacientes.twig', $context);
        $response->write($view);


        return $response;
    }
}