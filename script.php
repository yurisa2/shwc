<?php
ini_set("error_reporting",E_ALL);
ini_set('display_errors', 1);

require_once 'include/all_include.php';

echo "<pre>";

$pdor = new ShPDO;
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
  echo 'Problema ao Atualizar: ',  $e->getMessage(), "\n";
}
$page = $page+1;
file_put_contents("include/files/product_page.json",$page);

// $product_index = array_search($next_product,$productId_list)+1;

// put on json next id of product to be updated
// file_put_contents("include/files/next_update_product.json",$productId_list[$product_index]);
?>
