<head>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link href="include/style/formcontrol.css" rel="stylesheet" id="bootstrap-css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- Include the above in your HEAD tag ---------->
</head>
<body>
  <?php
  ini_set("error_reporting",E_ALL);
  ini_set('display_errors', 1);
  require 'include/all_include.php';

  $b2w_rest = new order;
  $order_data = $b2w_rest->get_orders()->orders;

  if($order_data !== "false")
  {
    foreach ($order_data as $key => $value)
    {
      $ids_order[] = array('updated_at' => $order_data[$key]->updated_at,'code' => $order_data[$key]->code);
      $list_order = $ids_order;
    }
    sort($list_order);

    foreach ($list_order as $key => $value) {
      $ids[] = $value['code'];
    }
    $list_orderids = $ids;

    $last_order_verified = json_decode(file_get_contents("include/files/last_verified_order.json"));
    $index = array_search($last_order_verified,$list_orderids);
    if($index+1 == count($list_orderids)) return false;
    else $nextorder_to_verify = $list_orderids[$index+1];

  // get informations about order id and decodes it
  $dados_pedido = $b2w_rest->get_order_information($nextorder_to_verify);
  // echo "<pre>";             //DEBUG
  // var_dump($dados_pedido);  //DEBUG
  // if the variable erro is set on url, do echo with mensage "DO NOT POSSIBLE ADD NFE KEY ON THIS ORDER ID"
  if(isset($_GET['erro'])){
    echo '  <div class="container contact-form">
    <div class="contact-image">
    <img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact"/>
    </div>
    <form method="get" action="perguntas_respostas_be.php">
    <h3>Adicionar informações da nota fiscal</h3>
    <div class="row">
    <div class="col-md-12">
    <h2>NÃO FOI POSSÍVEL ADICIONAR A NFE AO PEDIDO</h2>
    </div>
    </div>
    </form>
    </div>';
  }else{
    // if the variable erro isn't set, verify if invoices property is empty and type property is iguals of APPROVED
    // var_dump($dados_pedido->status->type);
    if((empty($dados_pedido->invoices)) && ($dados_pedido->status->type == "APPROVED")){
      $id_pedido = $dados_pedido->code;
      ?>
      <div class="container contact-form">
        <div class="contact-image">
          <img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact"/>
        </div>
        <form method="get" action="nfe_be.php">
          <h3>Adicionar informações da nota fiscal</h3>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <h2>Código do Pedido</h2>
                <input type="text" name="id_pedido" class="form-control" hidden='true' readyonly="true" value="<?php echo $id_pedido; ?>"/>
                <label type="text" name="id_pedido" class="form-control" readyonly="true" value=""><?php echo $id_pedido; ?></label><br>
                <h2>Produto(S)</h2>
                <?php
                foreach ($dados_pedido->items as $key => $value) {
                  echo '<label type="text" name="produto" class="form-control" readyonly="true" value="">'.$dados_pedido->items[$key]->name.'</label><br>';
                }?>
              </div>
              <div class="radio" class="col-md-4">
                <h2>NFE</h2>
                <textarea name="nfe" value="" cols="75" rows="1"></textarea><br><br>
              </div>
              <div class="form-group">
                <input type="submit" name="btnSubmit" class="btnContact" value="Adicionar"/>
              </div>
            </div>
          </div>
        </form>
      </div>
      <?php
    }else{
      // if invoices property isn't empty and type property is different of APPROVED, do echo with the html and message Have not orders without NFE key
      echo '<div class="container contact-form">
      <div class="contact-image">
      <img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact"/>
      </div>
      <form method="get" action="perguntas_respostas_be.php">
      <h3>Adicionar informações da nota fiscal</h3>
      <div class="row">
      <div class="col-md-12">
      <h2>NÃO HÁ PEDIDOS SEM A CHAVE NF2</h2>
      </div>
      </div>
      </form>
      </div>';
    }
  }
}else{
  // if invoices property isn't empty and type property is different of APPROVED, do echo with the html and message Have not orders without NFE key
  echo '<div class="container contact-form">
  <div class="contact-image">
  <img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact"/>
  </div>
  <form method="get" action="perguntas_respostas_be.php">
  <h3>Adicionar informações da nota fiscal</h3>
  <div class="row">
  <div class="col-md-12">
  <h2>NÃO HÁ PEDIDOS SEM A CHAVE NF2</h2>
  </div>
  </div>
  </form>
  </div>';
}?>
</body>
