<?php
ini_set("error_reporting",E_ALL);
ini_set('display_errors', 1);

require_once 'include/all_include.php';

echo "<pre>";

$pdor = new ShPDO;

var_dump($pdor->get_prod_qty("5392"));

var_dump($pdor->get_prod_price("5392"));



?>
