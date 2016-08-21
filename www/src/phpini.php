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