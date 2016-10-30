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


        $pNome = $request->getParam('nome');
        $usuarios = [];


        if (is_string($pNome))
        {
            $usuarios = $this->getByNome($pNome);
        }


        $context['usuarios'] = $usuarios;
        $context['busca'] = $pNome;


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('busca/usuarios/usuarios.twig', $context);
        $response->write($view);


        return $response;
    }


    private function getByNome($nome)
    {
        /** @var \PDO $conn */


        $sql = "
            SELECT
                u.id
                ,u.nome
                ,u.email
            FROM tb_usuario u
            WHERE
                u.id_tipo <> 1
                AND u.nome ILIKE :nome
            ORDER BY
                u.nome
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nome', "%{$nome}%");
        $stmt->execute();
        $ret = $stmt->fetchAll();


        return $ret;
    }
}