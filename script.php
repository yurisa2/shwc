<?php
ini_set("error_reporting",E_ALL);
ini_set('display_errors', 1);

require_once 'include/all_include.php';
echo "<pre>";

// PRODUCTS
$obj = new b2w_product;
$obj = $obj->updateproduct();
if(!$obj) echo "Erro ao atualizar produtos";

var_dump($obj);

// ORDERS
$obj = new b2w_order;
$obj = $obj->createorder();
if(!$obj) echo "Erro ao cadastrar o pedido no magento";

var_dump($obj);

// IBVOICES
$obj = new b2w_invoice;
$obj = $obj->verify_envoice();
if(!$obj) echo "Não há pedidos se a chave da nota fiscal";

var_dump($obj);

?>
