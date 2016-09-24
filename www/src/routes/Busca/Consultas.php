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


class Consultas extends Handle
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
                    ct.DT_CONSULTA AS data
                    ,TIME_FORMAT(h.HORAS, '%H:%i') AS horas
                    ,co.ID AS co_id
                    ,co.NOME AS co_nome
                    ,p.NOME AS pac_nome
                FROM TB_CONSULTA ct
                JOIN TB_PACIENTE p ON (p.ID = ct.ID_PACIENTE)
                JOIN TB_HORARIO h ON (h.ID = ct.ID_HORARIO)
                JOIN TB_CONSULTORIO co ON (co.ID = h.ID_CONSULTORIO)
                WHERE
                    p.NOME LIKE :NOME
                ORDER BY
                    ct.DT_CONSULTA DESC
                    ,h.HORAS
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('NOME', "%{$pNome}%");
        }
        else
        {
            $sql = "
                SELECT
                    ct.DT_CONSULTA AS data
                    ,TIME_FORMAT(h.HORAS, '%H:%i') AS horas
                    ,co.ID AS co_id
                    ,co.NOME AS co_nome
                    ,p.NOME AS pac_nome
                FROM TB_CONSULTA ct
                JOIN TB_PACIENTE p ON (p.ID = ct.ID_PACIENTE)
                JOIN TB_HORARIO h ON (h.ID = ct.ID_HORARIO)
                JOIN TB_CONSULTORIO co ON (co.ID = h.ID_CONSULTORIO)
                ORDER BY
                    ct.DT_CONSULTA DESC
                    ,h.HORAS
            ";
            $stmt = $conn->prepare($sql);
        }


        $stmt->execute();
        $context['consultas'] = $stmt->fetchAll();


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('busca/consultas/consultas.twig', $context);
        $response->write($view);


        return $response;
    }
}