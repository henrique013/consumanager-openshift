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
                    u.id
                    ,u.nome
                    ,u.email
                FROM tb_usuario u
                WHERE
                    u.nome ILIKE :nome
                ORDER BY
                    u.nome
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('nome', "%{$pNome}%");
        }
        else
        {
            $sql = "
                SELECT
                    u.id
                    ,u.nome
                    ,u.email
                FROM tb_usuario u
                ORDER BY
                    u.nome
            ";
            $stmt = $conn->prepare($sql);
        }


        $stmt->execute();
        $context['usuarios'] = $stmt->fetchAll();
        $context['busca'] = $pNome;


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('busca/usuarios/usuarios.twig', $context);
        $response->write($view);


        return $response;
    }
}