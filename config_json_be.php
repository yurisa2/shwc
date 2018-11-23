<?php
ini_set("error_reporting",E_ALL);
ini_set('display_errors', 1);
require_once 'include/all_include.php';
echo "<pre>";
if(isset($_GET['backup'])){
  if($_GET['backup'] == 'sim'){
    $backup = file_get_contents("include/backup.config.json");
    var_dump(file_put_contents("include/config.json",$backup));
    exit;
  }
}
$config_json = json_decode(file_get_contents("include/config.json"),true);
$i = 0;
foreach ($config_json as $key => $value) {
  if(gettype($value) == 'string'){
    if($value == 1) $array[$key] = true;
    if($value == 0) $array[$key] = false;
  }elseif($key == 'email_destinatario') {
    foreach ($value as $i => $value) {
      if(strpos($_GET["email_destinatario"],',')) $array['email_destinatario'] = explode(',',$_GET["email_destinatario"]);
      else $array['email_destinatario'][] = $_GET["email_destinatario_$i"];
    }
  }elseif($key == 'email_nfe') {
    foreach ($value as $i => $value) {
      if(strpos($_GET["email_nfe"],',')) $array['email_nfe'] = explode(',',$_GET["email_nfe"]);
      else $array['email_nfe'][] = $_GET["email_nfe_$i"];
    }
  }elseif($key == 'email_novacompra') {
    foreach ($value as $i => $value) {
      if(strpos($_GET["email_novacompra"],',')) $array['email_novacompra'] = explode(',',$_GET["email_novacompra"]);
      else $array['email_novacompra'][] = $_GET["email_novacompra_$i"];
    }
  } elseif($key == 'atualizar') {
    foreach ($value as $i => $value) {
      $array[$key]->$i = (bool)$_GET[$i];
    }
  }else {
    $array[$key] = $_GET[$key];
  }
}

if (isset($_GET['backup'])) {
  var_dump(file_put_contents("include/backup.config.json", json_encode($array)));
}

var_dump($array);
$config = file_put_contents("include/config.json", json_encode($array));
var_dump($config);
echo '<head>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<pre style="padding:5px;">';
var_dump(file_get_contents("include/config.json"));


echo '<a href="config_json.php"><button>Voltar</button></a>';
?>
