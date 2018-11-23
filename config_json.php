<?php ini_set("error_reporting",E_ALL);
ini_set('display_errors', 1);?>
<head>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link href="include/style/formcontrol.css" rel="stylesheet" id="bootstrap-css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <!-- Include the above in your HEAD tag ---------->
</head>

<body>
  <?php $config_json = json_decode(file_get_contents("include/config.json")); ?>
  <div class="container contact-form">
    <div class="contact-image">
      <img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact"/>
    </div>
    <form method="get" action="config_json_be.php">
      <div class="v">
        <h3>Configuração de variáveis - Mercado Livre - Magento</h3>
        <div class="col-md-12">
          <div class="form-group">
            <div class="col-md-12 col-sm-12">
              <!-- <button class="btnContact" type="button" onclick="Mudarestado('variavel')" style="width:300px;height:50px">Mostrar Variáveis</button> -->
              <label class="" style="width:inherit;" ><h2>Variáveis</h2></label>
              <div id="variavel" class="col-md-12">
                <?php
                $email_destinatario = [];
                $atualiza = [];
                $email_novacompra = [];
                foreach ($config_json as $key => $value) {

                  echo '<div class="" style="border:2px solid red;padding:5px;">';
                  echo '<label class="col-md-6">'.$key.'</label><br>';
                  if($key == "configmail" || $key == "SMTP" || $key == "verifica_nfe") {
                    echo '<input class="col-md-6" name="'.$key.'" value="'.$value.'"></input><br>';
                  }
                  if($key == 'email_destinatario' && gettype($value) == 'array'){
                    foreach ($value as $i => $val) {
                      $email_destinatario[] = $val;
                    }
                    $email_destinatario = implode(',',$email_destinatario);
                    echo '<input class="col-md-6" style="background-color:aqua" name='.$key.' value='.$email_destinatario.'></input>';
                    echo '<br>';
                  }elseif($key == 'email_nfe' && gettype($value) == 'array') {
                    foreach ($value as $i => $val) {
                      $email_email_nfe[] = $val;
                    }
                    $email_email_nfe = implode(',',$email_email_nfe);
                    echo '<input class="col-md-6" style="background-color:lightgreen" name='.$key.' value='.$email_email_nfe.'></input>';
                    echo '<br>';
                  }elseif($key == 'email_novacompra' && gettype($value) == 'array') {
                    foreach ($value as $i => $val) {
                      $email_novacompra[$i] = $val;
                    }
                    $email_novacompra = implode(',',$email_novacompra);
                    echo '<input class="col-md-6" style="background-color:#F1C497" name='.$key.' value='.$email_novacompra.'></input>';
                    echo '<br>';
                   }
                   elseif($key = 'atualizar' && gettype($value) == 'object') {
                    foreach ($value as $i => $val) {
                      if($val){
                        echo '<label>'.strtoupper($i).'</label><select name="'.$i.'">
                                <option value="1" selected>sim</option>
                                <option value="0">não</option>
                              </select><br>';
                        } else {
                          echo '<labe>'.strtoupper($i).'</label><select name="'.$i.'">
                                  <option value="1" >sim</option>
                                  <option value="0" selected>não</option>
                                </select><br>';
                        }
                    }
                    echo '<br>';
                  }
                  echo '</div>';
                }?>
              </div>
            </div>
            <br>
        <label>Voltar Valores Padrão</label>
        <input type="radio" name="backup" value="sim"/> <label>SIM</label>
        <input type="radio" name="backup" value="nao"/> <label>NÂO</label>
        <br>
        <label>Atualizar backup</label>
        <input type="radio" name="backup" value="1"/> <label>SIM</label>
        <input type="radio" name="backup" value="0"/> <label>NÂO</label>
              <div class="row">
              <input type="submit" name="btnSubmit" class="btnContact" style="width:inherit;" value="ALTERAR"/>
              </div>

          </div>

        </div>

      </div>




    </form>
  </div>
</body>
