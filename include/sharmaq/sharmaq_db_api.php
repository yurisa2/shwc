<?php

class sharmaq extends ShPDO {

  function __construct() {
    parent::__construct();
  }

  public function delete_updated($id) {



  }

  function query_prod($num){
    $stmt = $this->conn->prepare("Select * from ITENS where NUMERO = $num limit 1");
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
  }


  function get_prod_qty($num)  {
    $query_prod = $this->query_prod($num);
    $return = $query_prod['ESTOQUE_DISP'];
    // $return = $query_prod;

    return $return;
  }

  function get_prod_price($num)  {
    $query_prod = $this->query_prod($num);
    $return = $query_prod['PRECO_1'];

    return $return;
  }

}


?>
