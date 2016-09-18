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


class Usuarios extends Handle
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
                    u.ID AS id
                    ,u.NOME AS nome
                    ,u.EMAIL AS email
                FROM TB_USUARIO u
                WHERE
                    u.NOME LIKE :NOME
                ORDER BY
                    u.NOME
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('NOME', "%{$pNome}%");
        }
        else
        {
            $sql = "
                SELECT
                    u.ID AS id
                    ,u.NOME AS nome
                    ,u.EMAIL AS email
                FROM TB_USUARIO u
                ORDER BY
                    u.NOME
            ";
            $stmt = $conn->prepare($sql);
        }


        $stmt->execute();
        $context['usuarios'] = $stmt->fetchAll();


        $twig = $this->ci->get('twig');
        $view = $twig->render('busca/usuarios/usuarios.twig', $context);
        $response->getBody()->write($view);


        return $response;
    }
}