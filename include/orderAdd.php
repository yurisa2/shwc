<?php

class Magento_order{
  /**
  * Construtor. Set properties in Magento_order
  * @param object $dadosVenda;
  */

  public function __construct($dadosVenda)
  {
    global $magento_soap_user;
    global $magento_soap_password;
    global $store_id;
    global $DEBUG;


    $this->data = new stdClass();
    $this->data->id_order = $dadosVenda->id_order;
    // $this->data->mlb_produto = $dadosVenda->mlb_produto;
    $this->data->sku_produto = $dadosVenda->sku_produto;
    $this->data->nome_produto = $dadosVenda->nome_produto;
    $this->data->qtd_produto = $dadosVenda->qtd_produto;
    // $this->data->preco_unidade_produto =$dadosVenda->preco_unidade_produto;
    // $this->data->preco_total_produto = $dadosVenda->preco_total_produto;

    //--------------PAGAMENTO---------
    // $this->data->id_meio_pagamento = $dadosVenda->id_meio_pagamento;
    $this->data->tipo_pagamento = $dadosVenda->tipo_pagamento;
    // $this->data->custo_envio = $dadosVenda->custo_envio;
    // $this->data->total_pagar = $dadosVenda->total_pagar;
    // $this->data->status_pagamento = $dadosVenda->status_pagamento;

    //-----------ENDEREÇO ENTREGA---------
    $this->data->shipping_receptor = $dadosVenda->shipping_receptor;
    $this->data->shipping_rua = $dadosVenda->shipping_rua;
    $this->data->shipping_numero = $dadosVenda->shipping_numero;
    $this->data->shipping_bairro = $dadosVenda->shipping_bairro;
    $this->data->shipping_cep = $dadosVenda->shipping_cep;
    $this->data->shipping_cidade = $dadosVenda->shipping_cidade;
    $this->data->shipping_estado = $dadosVenda->shipping_estado;
    $this->data->shipping_pais = $dadosVenda->shipping_pais;
    $this->data->shipping_phone = $dadosVenda->shipping_phone;

    //-----------ENDEREÇO COBRANÇA---------
    $this->data->billing_receptor = $dadosVenda->billing_receptor;
    $this->data->billing_rua = $dadosVenda->billing_rua;
    $this->data->billing_numero = $dadosVenda->billing_numero;
    $this->data->billing_bairro = $dadosVenda->billing_bairro;
    $this->data->billing_cep = $dadosVenda->billing_cep;
    $this->data->billing_cidade = $dadosVenda->billing_cidade;
    $this->data->billing_estado = $dadosVenda->billing_estado;
    $this->data->billing_pais = $dadosVenda->billing_pais;
    $this->data->billing_phone = $dadosVenda->billing_phone;

    // ---------USUARIO---------
    // $this->data->id_comprador = $dadosVenda->id_comprador;
    // $this->data->apelido_comprador = $dadosVenda->apelido_comprador;
    $this->data->email_comprador = $dadosVenda->email_comprador;
    // $this->data->cod_area_comprador = $dadosVenda->cod_area_comprador;
    $this->data->telefone_comprador = $dadosVenda->telefone_comprador;
    $this->data->nome_comprador = $dadosVenda->nome_comprador;
    $this->data->sobrenome_comprador = $dadosVenda->sobrenome_comprador;
    // $this->data->tipo_documento_comprador = $dadosVenda->tipo_documento_comprador;
    $this->data->numero_documento_comprador = $dadosVenda->numero_documento_comprador;
  }

  public function magento1_customerCustomerCreate(){
    global $DEBUG;
    $obj_magento = magento_obj();
    $session = magento_session();

    $customer = array(
      'firstname' => $this->data->nome_comprador,
      'lastname' => $this->data->sobrenome_comprador,
      'email' => $this->data->email_comprador,
      'telephone' => $this->data->telefone_comprador['0'],
      'taxvat' => $this->data->numero_documento_comprador,
      'group_id' => "1",
      'store_id' => "22",
      'website_id' => "3"
    );

    $complexFilter = array(
      'complex_filter' => array(
        array(
          'key' => 'email',
          'value' => array('key' => 'in', 'value' => $customer['email'])
        )
      )
    );

    $return = $obj_magento->customerCustomerList($session, $complexFilter);
    //VERIFICAÇÃO SE EXISTE CLIENTE CADASTRADO COM O EMAIL NO MGNT
    //CASO NÃO EXISTA É CADASTRADO E É PEGO O ID DO CLIENTE
    if(!$return)
    {
      // function magento_customerCustomerCreate()
      $id_customer = $obj_magento->customerCustomerCreate($session, $customer);
      if($id_customer) echo "Customer Cadastrado com sucesso->ID: ".$id_customer;

      if($DEBUG == TRUE)
      {
        echo "<br/><h1>id Customer Novo</h1>";
        var_dump($id_customer);
      }

      $customer_address = array(
        'firstname' => $this->data->nome_comprador,
        'lastname' => $this->data->sobrenome_comprador,
        'street' => array($this->data->billing_rua.", ".$this->data->billing_numero." - ".$this->data->billing_bairro,''),
        'city' => $this->data->billing_cidade,
        'country_id' => $this->data->billing_pais,
        'region' => $this->data->billing_estado,
        'postcode' => $this->data->billing_cep,
        'telephone' => $this->data->telefone_comprador['0'],
        'is_default_billing' => TRUE,
        'is_default_shipping' => TRUE);

        if($DEBUG == TRUE) var_dump($customer_address);

        $return = $obj_magento->customerAddressCreate($session, $id_customer, $customer_address);

        //Se na requisição para atualizar o produto houver problema (retorno dif de 200)
        // ele entra no bloco de código
        if(gettype($return) !== 'integer')
        {
          $nome_funcao = "magento1_customerCustomerCreate";
          $saida = $return->faultstring;
          $titulo = "Erro no Script Integração SKYHUB Magento";
          mandaEmail_files_db($nome_funcao,$saida,$titulo);
          return 0;
        }
        else
        {
          echo "Criado customer Address";
          return $id_customer;
          if($DEBUG == TRUE) echo "<br/><h1>AddressesCreate ".$return."</h1>";
        }
      }
      else
      {
        $id_customer = $return[0]->customer_id;
        echo "Id customer::: ";
        return $id_customer;
        if($DEBUG == TRUE) echo "<h1>Customer</h1>";
        if($DEBUG == TRUE) var_dump($id_customer);
      }
    }

    public function magento2_customerAddressCreate($id_customer)
    {
      global $DEBUG;
      $obj_magento = magento_obj();
      $session = magento_session();

      $obj_mag = $obj_magento->customerAddressList($session, $id_customer);
      if($DEBUG == TRUE) {echo "<h1>addressesList</h1>";var_dump($obj_mag);}
      var_dump($obj_mag);
      $obj_mag_email = $obj_magento->customerCustomerInfo($session, $id_customer);
      $obj_mag = $obj_mag['0'];
      var_dump($obj_mag_email);
      if(!$obj_mag_email || !$obj_mag){
        $nome_funcao = "magento2_customerAddressCreate";
        $saida = $obj_mag_email->faultstring;
        $saida .= $obj_mag->faultstring;
        $titulo = "Erro no Script Integração SKYHUB Magento";
        mandaEmail_files_db($nome_funcao,$saida,$titulo);
        return 0;
      }

      if($DEBUG == TRUE)
      {
        echo "<h1>CustomerInfo</h1>";
        var_dump($obj_mag);
      }

      $name = $obj_mag->firstname." ".$obj_mag->lastname;
      $email = $obj_mag_email->email;
      $document = preg_replace('/\D/', '',$obj_mag_email->taxvat);
      $city = $obj_mag->city;
      $region = $obj_mag->region;
      $postcode = preg_replace('/\D/', '',$obj_mag->postcode);
      $street = $obj_mag->street;
      $phone = preg_replace('/\D/', '',$obj_mag->telephone);

      $return = array(
        'name' => $name,
        'email' => $email,
        'document' => $document,
        'city' => $city,
        'region' => $region,
        'postcode' => $postcode,
        'street' => $street,
        'phone' => $phone,
      );

      if($DEBUG == true){ echo "<h1>Array Customer</h1>";var_dump($return);}
      return $return;
    }

    public function magento3_shoppingCartCreate()
    {
      global $DEBUG;
      global $store_id;
      $obj_magento = magento_obj();
      $session = magento_session();

      $cart_id = $obj_magento->shoppingCartCreate($session, $store_id);

      if($cart_id) echo "<br/>ID do Carrinho de Compras: ".$cart_id;
      else{
        $nome_funcao = "magento3_shoppingCartCreate";
        $saida = $cart_id->faultstring;
        $titulo = "Erro no Script Integração SKYHUB Magento";
        mandaEmail_files_db($nome_funcao,$saida,$titulo);
        return 0;
      }
      return $cart_id;
      if($DEBUG == TRUE) {echo "<h1>shoppingCartCreate</h1>";var_dump($cart_id);}
    }

    public function magento4_shoppingCartProductAdd($cart_id)
    {
      global $DEBUG;
      global $store_id;
      $obj_magento = magento_obj();
      $session = magento_session();

      if(gettype($this->data->sku_produto) == 'array'){
      foreach ($this->data->sku_produto as $key => $value)
      {
        $shoppingCartProductEntity[$key] = array(
          'sku' => $this->data->sku_produto[$key],
          'qty' => $this->data->qtd_produto[$key]);
      }
    }else {
      $shoppingCartProductEntity[] = array(
        'sku' => $this->data->sku_produto,
        'qty' => $this->data->qtd_produto);
    }
        $result_prod_add = $obj_magento->shoppingCartProductAdd($session, $cart_id, $shoppingCartProductEntity, $store_id);
        var_dump($result_prod_add);
        if ($result_prod_add === true)
        {
          echo "<br/>Itens adicionados no Carrinho: ";
          return 1;
        }
        else
        {
          echo "<br/>Produtos não puderam ser adicionados";var_dump($result_prod_add);
          $nome_funcao = "magento4_shoppingCartProductAdd";
          $saida = $result_prod_add->faultstring;
          $titulo = "Erro no Script Integração SKYHUB Magento";
        mandaEmail_files_db($nome_funcao,$saida,$titulo);
        return 0;
        }
      }

      public function magento5_shoppingCartProductList($cart_id)
      {
        global $DEBUG;
        global $store_id;
        $obj_magento = magento_obj();
        $session = magento_session();
        $result = $obj_magento->shoppingCartProductList($session, $cart_id, $store_id);
        if($DEBUG == TRUE)
        {
          echo "<h1>Produtos adicionados no carrinho: </h1>";
          var_dump($result);
        }

        //Se na requisição para atualizar o produto houver problema (retorno dif de 200)
        // ele entra no bloco de código
        if(gettype($result) != 'array')
        {
          $nome_funcao = "magento5_shoppingCartProductList";
          $saida = $result->faultstring;
          $titulo = "Erro no Script Integração SKYHUB Magento";
          mandaEmail_files_db($nome_funcao,$saida,$titulo);
          return 0;
        }
        else return "Produtos adicionados no carrinho";
      }

      public function magento6_shoppingCartCustomerSet($cart_id, $id_customer)
      {
        global $DEBUG;
        global $store_id;
        $obj_magento = magento_obj();
        $session = magento_session();

        $customer = array(
          'customer_id' => $id_customer,
          'mode' => "customer"
        );

        $return = $obj_magento->shoppingCartCustomerSet($session, $cart_id, $customer, $store_id);
        if ($return == true)
        {
          return "Setado Customer com sucesso: ";
        }
        else
        {
          $nome_funcao = "magento6_shoppingCartCustomerSet";
          $saida = $return->faultstring;
          $titulo = "Erro no Script Integração SKYHUB Magento";
          mandaEmail_files_db($nome_funcao,$saida,$titulo);
          echo "<br/>Não foi possível Setar Customer";
          return 0;
        }
        if($DEBUG == TRUE) echo "<h1>CartCustomerSet: ".$return."</h1>";
      }
      public function magento7_shoppingCartCustomerAddresses($cart_id)
      {
        global $DEBUG;
        global $store_id;
        $obj_magento = magento_obj();
        $session = magento_session();

        $nome_sobrenome_billing = "B2W-".ucwords(strtolower($this->data->billing_receptor));
        $nome_billing = explode(' ', $nome_sobrenome_billing);
        $sobrenome_billing = array_splice($nome_billing, -1);
        $nome_billing = implode(' ',$nome_billing);
        $sobrenome_billing = implode(' ',$sobrenome_billing);

        $nome_sobrenome_shipping = "B2W-".ucwords(strtolower($this->data->shipping_receptor));
        $nome_shipping = explode(' ', $nome_sobrenome_shipping);
        $sobrenome_shipping = array_splice($nome_shipping, -1);
        $nome_shipping = implode(' ',$nome_shipping);
        $sobrenome_shipping = implode(' ', $sobrenome_shipping);

        $billing = array(
          array(
            'mode' => 'billing',
            'firstname' => $nome_billing,
            'lastname' => $sobrenome_billing,
            'street' => $this->data->billing_rua.", ".$this->data->billing_numero." - ".$this->data->billing_bairro,
            'city' => $this->data->billing_cidade,
            'region' => $this->data->billing_estado,
            'postcode' => $this->data->billing_cep,
            'country_id' => $this->data->billing_pais,
            'telephone' => $this->data->billing_phone,
            'is_default_billing' => TRUE,
            'is_default_shipping' => FALSE),
            array(
              'mode' => 'shipping',
              'firstname' => $nome_shipping ,
              'lastname' => $sobrenome_shipping,
              'street' => $this->data->shipping_rua.", ".$this->data->shipping_numero."-".$this->data->shipping_bairro,
              'city' => $this->data->shipping_cidade,
              'region' => $this->data->shipping_estado,
              'postcode' => $this->data->shipping_cep,
              'country_id' => $this->data->shipping_pais,
              'telephone' => $this->data->shipping_phone,
              'is_default_billing' => FALSE,
              'is_default_shipping' => TRUE)
            );

            $return = $obj_magento->shoppingCartCustomerAddresses($session, $cart_id, $billing, $store_id);

            if ($return === true) return "Setado Customer Addresses no carrinho";
            else {
              $nome_funcao = "magento7_shoppingCartCustomerAddresses";
              $saida = $return->faultstring;
              $titulo = "Erro no Script Integração SKYHUB Magento";
              mandaEmail_files_db($nome_funcao,$saida,$titulo);
              echo "Nao deu para setar Address no carrinho";
              return 0;
            }

          }
          public function magento8_shoppingCartShippingMethod($cart_id)
          {
            global $DEBUG;
            global $store_id;
            global $shipping_method;
            $obj_magento = magento_obj();
            $session = magento_session();
            $return = $obj_magento->shoppingCartShippingMethod($session, $cart_id, $shipping_method, $store_id);

            if ($return == true) return "Setado Shipping Method para o carrinho".var_dump($return);
            else {
              $nome_funcao = "magento8_shoppingCartShippingMethod";
              $saida = $return->faultstring;
              $titulo = "Erro no Script Integração SKYHUB Magento";
              mandaEmail_files_db($nome_funcao,$saida,$titulo);
              echo "Não foi possivel acionar o metodo de entrega";
              return 0;
            }
            if($DEBUG == TRUE)
            {
              echo "<h1>shoppingCartShippingMethod</h1>";
              var_dump($return);
            }

          }
          public function magento9_shoppingCartPaymentMethod($cart_id)
          {
            global $DEBUG;
            global $store_id;
            $obj_magento = magento_obj();
            $session = magento_session();

            $payment = array(
              'po_number' => null,
              'method' => 'cashondelivery',
              'cc_cid' => null,
              'cc_owner' => null,
              'cc_number' => null,
              'cc_type' => null,
              'cc_exp_year' => null,
              'cc_exp_month' => null
            );

            $return =  $obj_magento->shoppingCartPaymentMethod($session, $cart_id, $payment, $store_id);

            if ($return == true) return "Setado Payment Method para o carrinho<br/>";
            else {
              $nome_funcao = "magento9_shoppingCartPaymentMethod";
              $saida = $return->faultstring;
              $titulo = "Erro no Script Integração SKYHUB Magento";
              mandaEmail_files_db($nome_funcao,$saida,$titulo);
              echo "Problema meio de pagamento";
              return 0;
            }
            if($DEBUG == TRUE)
            {
              echo "<h1>ShoppingCartPaymentMetod</h1>";
              var_dump($return);
            }
          }

          public function magento10_shoppingCartOrder($cart_id)
          {
            global $DEBUG;
            global $store_id;
            $obj_magento = magento_obj();
            $session = magento_session();

            $order_id = $obj_magento->shoppingCartOrder($session, $cart_id, $store_id);
            if($DEBUG == true){
              if(strlen($order_id) < 11) echo "<br/>Order criado - ".$order_id;
              else {
                $nome_funcao = "magento10_shoppingCartOrder";
                $saida = $order_id->faultstring;
                $titulo = "Erro no Script Integração SKYHUB Magento";
                mandaEmail_files_db($nome_funcao,$saida,$titulo);
                echo '<br/>Deu problema no final--> '.$order_id;
                return 0;
              }
            }
            if($DEBUG == TRUE) {echo "<h1>shoppingCartOrder</h1>";var_dump($order_id);}

            //function magento_salesOrderAddComment($order_id, $status, $comment)
            $comment = "Id do Pedido B2W: ".$this->data->id_order;
            // foreach ($this->data->id_order as $key =>$value)
            // {
            //   $comment .= "Id do Pedido MLB: ".$this->data->id_order[$key]."\t";
            // }

            $return = $obj_magento->salesOrderAddComment($session, $order_id, 'pending', $comment, null);
            if($DEBUG == TRUE)
            {
              if($return == true) echo "<br/>Comentário criado<br/>";
              else {
                $nome_funcao = "magento10_shoppingCartOrder";
                $saida = $return->faultstring;
                $titulo = "Erro no Script Integração SKYHUB Magento";
                mandaEmail_files_db($nome_funcao,$saida,$titulo);
                echo "Não foi possivel adicionar comentario<br/>";
                return 0;
              }
            }
            if($DEBUG == TRUE)
            {
              echo "<h1>salesOrderAddComment</h1><br/>";
              var_dump($return);
            }
            if((strlen($order_id) < 11) && ($return == true)){
              return $order_id;
            }
            else return 0;
          }
        }
