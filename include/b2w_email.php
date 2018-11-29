<?php
class email
{
  public function __construct($order_data)
  {
    $this->order_data = $order_data;
  }

  public function message()
  {
    $message_email = "Nome do Comprador: ".$this->order_data->nome_comprador." ".$this->order_data->sobrenome_comprador
    ."<br>Email do Comprador: ".$this->order_data->email_comprador
    ."<br>Telefone do Comprador: ".$this->order_data->telefone_comprador[0]
    ."<br>Documento do comprador: ".$this->order_data->numero_documento_comprador
    ."<br>Produto: ".$this->order_data->nome_produto
    ."<br>Sku: ".$this->order_data->sku_produto
    ."<br>Quantidade: ".$this->order_data->qtd_produto
    ."<br>Preço Especial do Produto: ".$this->order_data->preco_especial_produto
    ."<br>Preço Original do Produto: ".$this->order_data->preco_original_produto
    ."<br>Custo de Envio: ".$this->order_data->custo_envio
    ."<br>Pedido: ".$this->order_data->id_order
    ."<br>Pagamento: ".$this->order_data->tipo_pagamento
    ."<br>Parcelas: ".$this->order_data->parcels
    ."<br>Desconto: ".$this->order_data->desconto
    ."<br>Total a Pagar: ".$this->order_data->total_pagar
    ."<br>Receptor da Entrega: ".$this->order_data->shipping_receptor
    ."<br>Rua da Entrega: ".$this->order_data->shipping_rua
    ."<br>Número da Entrega: ".$this->order_data->shipping_numero
    ."<br>Bairro da Entrega: ".$this->order_data->shipping_bairro
    ."<br>Cep da Entrega: ".$this->order_data->shipping_cep
    ."<br>Cidade da Entrega: ".$this->order_data->shipping_cidade
    ."<br>Estado da Entrega: ".$this->order_data->shipping_estado
    ."<br>País da Entrega: ".$this->order_data->shipping_pais
    ."<br>Telefone de Contato: ".$this->order_data->shipping_phone
    ."<br>Referência do Local de Entrega: ".$this->order_data->shipping_referencia
    ."<br>Receptor da Cobrança : ".$this->order_data->billing_receptor
    ."<br>Rua da Cobrança : ".$this->order_data->billing_rua
    ."<br>Número da Cobrança : ".$this->order_data->billing_numero
    ."<br>Bairro da Cobrança : ".$this->order_data->billing_bairro
    ."<br>Cep da Cobrança : ".$this->order_data->billing_cep
    ."<br>Cidade da Cobrança : ".$this->order_data->billing_cidade
    ."<br>Estado da Cobrança : ".$this->order_data->billing_estado
    ."<br>País da Cobrança : ".$this->order_data->billing_pais
    ."<br>Telefone de Contato: ".$this->order_data->billing_phone
    ."<br>Referência do Local da Cobrança: ".$this->order_data->sbilling_referencia;
    return $message_email;
  }
}

?>
