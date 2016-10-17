<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 16/10/16
 * Time: 00:40
 */

namespace App\Route\Busca\Consultas;


use App\Util\Handle;
use App\Util\Handle\GET;
use Slim\Http\Request;
use Slim\Http\Response;


class Responsaveis extends Handle
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
                    rc.id
                    ,rc.nome
                    ,rc.telefone
                FROM tb_resp_consulta rc
                WHERE
                    rc.nome ILIKE :nome
                ORDER BY
                    rc.nome
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('nome', "%{$pNome}%");
        }
        else
        {
            $sql = "
                SELECT
                    rc.id
                    ,rc.nome
                    ,rc.telefone
                FROM tb_resp_consulta rc
                ORDER BY
                    rc.nome
            ";
            $stmt = $conn->prepare($sql);
        }


        $stmt->execute();
        $context['responsaveis'] = $stmt->fetchAll();
        $context['busca'] = $pNome;


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('busca/consultas/responsaveis/responsaveis.twig', $context);
        $response->write($view);


        return $response;
    }
}