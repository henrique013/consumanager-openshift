<?php
// php.ini


/*
 *---------------------------------------------------------------
 * ENCODING
 *---------------------------------------------------------------
 */
ini_set('default_charset', 'UTF-8');


/*
 *---------------------------------------------------------------
 * TIMEZONE
 *---------------------------------------------------------------
 */
date_default_timezone_set('America/Sao_Paulo');


/*
 *---------------------------------------------------------------
 * LOCALE
 *---------------------------------------------------------------
 */
setlocale(LC_TIME, 'pt_BR.utf-8', 'Portuguese_Brazil.1252');
setlocale(LC_CTYPE, 'pt_BR.utf-8', 'Portuguese_Brazil.1252');
setlocale(LC_COLLATE, 'pt_BR.utf-8', 'Portuguese_Brazil.1252');


/*
 *---------------------------------------------------------------
 * SESSION
 *---------------------------------------------------------------
 */
ini_set('session.use_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');
