<?php

namespace App\Util;

use Slim\Http\Response;


abstract class Pagination
{
    /**
     * @param Response $response
     * @param int $total_itens
     * @param int $current_page
     * @param int $limit_per_page
     */
    public static function addHeaders(Response &$response, $total_itens, $current_page, $limit_per_page)
    {
        /** @var \Slim\Http\Response $response */

        $pages = $total_itens / $limit_per_page;
        $pages = ceil($pages);

        $response = $response->withHeader('X-Pagination-Count', $total_itens);
        $response = $response->withHeader('X-Pagination-Current-Page', $current_page);
        $response = $response->withHeader('X-Pagination-Pages', $pages);
        $response = $response->withHeader('X-Pagination-Limit', $limit_per_page);
    }


    /**
     * @param int $page
     * @param int $limit_per_page
     * @return int
     */
    public static function offsetByPage($page, $limit_per_page)
    {
        return ($limit_per_page * $page) - $limit_per_page;
    }
}