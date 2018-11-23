<?php
class b2w_order extends order
{
  public function createorder()
  {
    if(ORDER) {
      $order_data = $this->get_orders()->orders;
      echo "<h2>Id dos pedidos B2W:</h2> <br>";
      // var_dump($ids_order);

      if($order_data !== "false")
      {
        echo "É diferente<br>";
        foreach ($order_data as $key => $value)
        {
          $ids_order[] = array('updated_at' => $order_data[$key]->updated_at,'code' => $order_data[$key]->code);
          $list_order = $ids_order;
        }
        sort($list_order);

        foreach ($list_order as $key => $value) {

          $ids[] = $value['code'];
        }
        $list_orderids = json_encode($ids);

        if(!file_exists("include/files/last_created_order.json")) file_put_contents("include/files/last_created_order.json",json_encode($ids_order[0]));

        $last_order_created = json_decode(file_get_contents("include/files/last_created_order.json"));

        $index = array_search($last_order_created,$list_orderids);
        if($index+1 == count($lista)) return "Sem novos pedidos";

        $next_order_id = $lista[$index+1];

        echo "<h3>Próximo Pedido: ".$next_order_id."</h3><br>";

        if(!file_exists("include/files/list_magento_orders.json")) file_put_contents("include/files/list_magento_orders.json",json_encode(''));

        $list_magento_orders = json_encode(file_get_contents("include/files/list_magento_orders.json"));

        if(!strpos($list_magento_orders, $next_order_id))
        {
          //PEGA OS DADOS DO PEDIDO
          $order_data = $this->get_order_information($next_order_id);
          echo "<h2>Dados do pedido a ser cadastrado</h2>";
          echo "<br>";
          var_dump($order_data);
          $buyer_name = $order_data->nome_comprador;
          $order_data_json = json_encode($order_data);

          $email_message = str_replace(",", "<br>", $dados_pedido_json);
          //CRIA ETIQUETA E MANDA POR EMAIL
          $label_data = get_order_label($next_order_id);

          if($label_data == null) $filename = null;
          else {
            $filename = "etiquetas/$next_order_id.pdf";
            file_put_contents($filename,$label_data);
          }
          $error_handling = new log("Novo Pedido SKYHUB", "Id Pedido: $next_order_id<br>", $email_message, "nova compra");
          $error_handling->log_email = true;
          $error_handling->mensagem_email = "Nova compra que entrou no magento";
          $error_handling->log_etiqueta = $filename;
          $error_handling->log_email = true;
          $error_handling->email_novacompra = true;
          $error_handling->dir_file = "log/log.json";
          $error_handling->log_files = true;
          $error_handling->send_log_email();
          $error_handling->execute();

          file_put_contents("include/files/last_created_order.json",json_encode($next_order_id));

          $magento_order = new Magento_order($dados_pedido);
          echo "<h2>1 - Criação do customer</h2>";
          // cria cadastro do comprador no magento
          // se ja for cadastrado apenas recupera o id do comprador
          // cria tbm o cadastro do endereço do comprador no magento
          // se for cadastrado recupera as informações
          $id_customer = $magento_order->magento1_customerCustomerCreate();
          var_dump($id_customer);
          if($id_customer == 0) return false;

          echo "<br/><h2>2 - Criação do endereço do customer</h2>";
          // Apenas cria um array com os dados do comprador
          $customer_address = $magento_order->magento2_customerAddressCreate($id_customer);
          var_dump($customer_address);
          if($customer_address == 0) return false;

          echo "<br/><h2>3 - Criação do carrinho de compras</h2>";
          // cria o carrinho de compras, retorna o id do carrinho
          $id_carrinho = $magento_order->magento3_shoppingCartCreate();
          var_dump($id_carrinho);
          if($id_carrinho == 0) return false;

          echo "<br/><h2>4 - Adicionando os podutos no carrinho</h2>";
          // adiciona os produtos no carrinho
          $add_produto = $magento_order->magento4_shoppingCartProductAdd($id_carrinho);
          if($add_produto == 0) return false;

          echo "<br/><h2>5 - Lista do podutos no carrinho</h2>";
          // lista os produtos no carrinho
          $produtos_carrinho = $magento_order->magento5_shoppingCartProductList($id_carrinho);
          var_dump($produtos_carrinho);
          if($produtos_carrinho === 0) return false;

          echo "<br/><h2>6 - Inicializando o customer (shoppingCartCustomerSet)</h2>";
          //seta o comprador com o carrinho
          $customerSet = $magento_order->magento6_shoppingCartCustomerSet($id_carrinho,$id_customer);
          var_dump($customerSet);
          if($customerSet === 0) return false;

          echo "<br/><h2>7 - Iniciando o endereço do customer no carrinho</h2>";
          //seta o endereço do comprador com o carrinho
          $customerAddressSet = $magento_order->magento7_shoppingCartCustomerAddresses($id_carrinho);
          var_dump($customerAddressSet);
          if($customerAddressSet === 0) return false;

          echo "<br/><h2>8 - Setando o método de entrega</h2>";
          //seta o meio de pagamento com o carrinho
          $customerEntregaSet = $magento_order->magento8_shoppingCartShippingMethod($id_carrinho);
          var_dump($customerEntregaSet);
          if($customerEntregaSet === 0) return false;

          echo "<br/><h2>9 - Setando o método de pagamento</h2>";
          //seta o meio de pagamento com o carrinho
          $customerPagamentoSet = $magento_order->magento9_shoppingCartPaymentMethod($id_carrinho);
          var_dump($customerPagamentoSet);
          if($customerPagamentoSet === 0) return false;

          echo "<br/><h2>7 - Finalização da compra</h2>";
          // Finaliza a compra
          $order = $magento_order->magento10_shoppingCartOrder($id_carrinho);
          var_dump($order);
          if($order == 0) return false;

          if($order != 0)
          {
            $order_list = (array) retornaPedidosB2W_MGT();
            $order_list[] = array('B2W' => $next_order_id);
            $conteudo_arquivo = file_put_contents("include/files/list_magento_orders.json", json_encode($order_list));

            $error_handling = new log("Novo Pedido MAGENTO", "Numero do Pedido MGT: $order", "Comprador: $buyer_name", "nova compra");
            $error_handling->log_email = true;
            $error_handling->mensagem_email = "Nova compra SKYHUB entrou no magento com sucesso";
            $error_handling->log_email = true;
            $error_handling->dir_file = "log/log.json";
            $error_handling->log_files = true;
            $error_handling->send_log_email();
            $error_handling->execute();
          }
        } else {
          echo "Pedido já cadastrado no Magento<br>";
          file_put_contents("include/files/last_created_order.json",json_encode($next_order_id));
        }
      } else echo "Sem novos pedidos<br>";
    } else echo '<h1>PEDIDO DESATIVADO</h1>';
  }
}

?>
