<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route;


use App\Util\Handle;
use App\Util\Handle\GET;
use DateTime;
use Slim\Http\Request;
use Slim\Http\Response;


class Agenda extends Handle
{
    use GET;


    function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \PDO $conn */


        $data = $request->getAttribute('data', date('Y-m-d'));


        $sql = "
            SELECT
              c.ID AS CO_ID
              ,c.NOME AS CO_NOME
              ,SUM(IF(ct.ID IS NULL, 1, 0)) AS HR_LIVRES
              ,SUM(IF(ct.ID IS NOT NULL, 1, 0)) AS HR_OCUPADOS
              ,COUNT(h.ID) AS HR_TOTAL
            FROM TB_CONSULTORIO c
            JOIN TB_HORARIO h ON (h.ID_CONSULTORIO = c.ID)
            LEFT JOIN TB_CONSULTA ct ON (ct.ID_HORARIO = h.ID AND ct.DT_CONSULTA = :DT_CONSULTA)
            GROUP BY
              c.ID
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('DT_CONSULTA', $data);
        $stmt->execute();


        $context['data'] = new DateTime($data);
        while ($row = $stmt->fetch())
        {
            $resumo = [
                'consultorio' => [
                    'id' => $row['CO_ID'],
                    'nome' => $row['CO_NOME']
                ],
                'ocupacao_horarios' => [
                    'livres' => $row['HR_LIVRES'],
                    'ocupados' => $row['HR_OCUPADOS'],
                    'total' => $row['HR_TOTAL']
                ],
            ];

            $context['resumos'][] = $resumo;
        }


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('agenda/agenda.twig', $context);
        $response->write($view);


        return $response;
    }
}