<?php
ini_set("error_reporting",E_ALL);
ini_set('display_errors', 1);
require_once 'include/all_include.php';
global $b2w_put;
$sec_ini = time();

// get order id and Strip whitespace from the beginning and end of a string
$id_pedido = trim($_GET["id_pedido"]);

// get nfe key and Strip whitespace from the beginning and end of a string
$nfe = trim($_GET["nfe"]);

// verify if the variables are empty
if(($nfe == '') || ($id_pedido == '')) {
  header('Location: nfe.php?erro');
}
echo '<head>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<pre style="padding:5px;">';

$body = array(
  //"status" => "payment_received",
  "invoice" => array(
    "key" => $nfe
  ));
$response = $b2w_put->post("/orders/$id_pedido/invoice", $body);
if(strpos($response->response_status_lines[0],"20")) header('Location: nfe.php');
else header('Location: nfe.php?erro');

?>
