<?php
ini_set("error_reporting",E_ALL);
ini_set('display_errors', 1);

require_once 'include/all_include.php';

echo "<pre>";

$pdor = new sharmaq;
$woocommerce_product = new products;
 if(!file_exists("include/files/product_page.json")) file_put_contents("include/files/product_page.json",1);
 $page = (int)file_get_contents("include/files/product_page.json");
 if($page > 18) $page = 1;
$product_list = $woocommerce_product->getproducts(array('page' => $page,'per_page' => 5));

foreach ($product_list as $key => $value) {
  $productId_list[] = $value->id;
}

 //  // if file doesn't exists, create it with first id of list of product id
 //  if(!file_exists("include/files/next_update_product.json")) file_put_contents("include/files/next_update_product.json",$productId_list[0]);
 //
 //  $next_product = file_get_contents("include/files/next_update_product.json");
 //
 //  $product_stock = $pdor->get_prod_qty($next_product);
 //
 // $product_price = $pdor->get_prod_price($next_product);
 // $product_data = array('price' => $product_price,'stock_quantity' => $product_stock);

 foreach ($productId_list as $key => $value) {
   $product_stock = $pdor->get_prod_qty($value);
   $product_price = $pdor->get_prod_price($value);
   $update_batch['update'][] =
       ['id' => $value,
        'price' => $product_price,
        'stock_quantity' => $product_stock];
 }
  $product_data = $update_batch;

try {
$return = $woocommerce_product->productbatch($product_data);

$updated_products='';
foreach ($return->update as $key => $value) {
  $updated_products .= $value->id.", ";
}
echo "Produtos Atualizados: $updated_products";
} catch (Exception $e) {
  $error_handling = new error_handling('Erro ao atualizar os produtos', "Erro ao atualizar os produtos: $updated_products", $e->getMessage(), 'erro');
  //lê o json que contem o time() do ultimo email enviado
if(!file_exists("include/files/last_emailsend.json")) file_put_contents("include/files/last_emailsend.json",json_encode(0));

$time_emailsend = json_decode(file_get_contents("include/files/last_emailsend.json"));
  //Se o horario do json + 1 hora (3600 s) for menor ou igual ao horario
  //atual significa que ja passou uma hora e pode mandar novamente email
  if ($time_emailsend + 3600 <= time())
  {
    //estancia a função para criar a mensagem de corpo
    $error_handling->send_error_email();
    //estancia a função para executar as funções email()-db()-files() previamente
    //por padrão, as propriedades error_db e error_files estão true
    $error_handling->execute();
    //atualiza o json para a hora em que é mandado o email
    file_put_contents("include/files/last_emailsend.json", json_encode(time()));
    return "0";
  }
  else
  {
    //Caso não tenha dado uma hora do ultimo email enviado, é gravado
    //o erro no json de log  error_files/error_log.json
    //executa a função para criar a mensagem de erro
    $error_handling->send_errorlog_email();
    //executa a função para atualizar o json com o novo erro
    $error_handling->files();
  }
  echo 'Problema ao Atualizar: ',  $e->getMessage(), "\n";
}
$page = $page+1;
file_put_contents("include/files/product_page.json",$page);

// $product_index = array_search($next_product,$productId_list)+1;

// put on json next id of product to be updated
// file_put_contents("include/files/next_update_product.json",$productId_list[$product_index]);
?>
