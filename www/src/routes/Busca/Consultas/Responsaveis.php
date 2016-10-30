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


        $pNome = $request->getParam('nome');
        $responsaveis = [];


        if (is_string($pNome))
        {
            $responsaveis = $this->getByNome($pNome);
        }


        $context['responsaveis'] = $responsaveis;
        $context['busca'] = $pNome;


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('busca/consultas/responsaveis/responsaveis.twig', $context);
        $response->write($view);


        return $response;
    }


    private function getByNome($nome)
    {
        /** @var \PDO $conn */


        $sql = "
            SELECT
                rc.id
                ,rc.nome
                ,rc.telefone
                ,rc.supervisor
            FROM tb_resp_consulta rc
            WHERE
                rc.nome ILIKE :nome
            ORDER BY
                rc.nome
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nome', "%{$nome}%");
        $stmt->execute();
        $ret = $stmt->fetchAll();


        return $ret;
    }
}