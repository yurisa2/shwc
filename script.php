<?php
ini_set("error_reporting",E_ALL);
ini_set('display_errors', 1);


require_once 'include/all_include.php';




echo "<pre>";

$pdor = new ShPDO;


$cod_prod = 48947;

var_dump($pdor->get_prod_qty($cod_prod));

var_dump($pdor->get_prod_price($cod_prod));



?>
