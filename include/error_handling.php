<?php
class error_handling extends event_base
{
  /**
  * Set Construtor
  * Use __constructor and methods of extended class event_base
  * @param string $titulo O assunto do email
  * @param string $nome_funcao A função que houve problema
  * @param string $saida O debug da função
  * @param string $tipo Qual a origem/significado da mensagem: Erro - log
  *
  */
  public function __construct($titulo, $nome_funcao, $saida, $tipo)
  {
    parent::__construct();
    $this->titulo = $titulo;
    $this->nome_funcao = $nome_funcao;
    $this->saida = $saida;
    $this->tipo = $tipo;
  }

  /**
  *
  * Funcao responsavel por criar o corpo do email
  * Exclusivamente usada na função files() do event_base
  */
  function send_errorlog_email()
  {
    $this->mensagem = array('Nome Funcao' =>$this->nome_funcao ,
    'Msg de Erro' =>$this->saida ,
    'Titulo' =>$this->titulo ,
    'Tipo do Erro' =>$this->tipo );
  }

  /**
  * Funcao responsavel por criar o corpo do email
  * Usada na função email() do event_base
  * Caso não esteja habilitado para mandar email, a mensagem se torna array
  * para facilitar a utilização nas funções db() e files()
  */
  function send_error_email()
  {
    $this->mensagem = json_encode(array('Nome Funcao' =>$this->nome_funcao ,
    'Msg de Erro' =>$this->saida ,
    'Titulo' =>$this->titulo ,
    'Tipo do Erro' =>$this->tipo));
    if($this->flag_HTML)
    {
      $this->mensagemHTML  ='
      <!doctype html>
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <meta name="viewport" content="initial-scale=1.0" />
      <meta name="format-detection" content="telephone=no" />
      <title></title>
      <style type="text/css">
      body {
        width: 100%;
        margin: 0;
        padding: 0;
        -webkit-font-smoothing: antialiased;
      }
      @media only screen and (max-width: 600px) {
        table[class="table-row"] {
          float: none !important;
          width: 98% !important;
          padding-left: 20px !important;
          padding-right: 20px !important;
        }
        table[class="table-row-fixed"] {
          float: none !important;
          width: 98% !important;
        }
        table[class="table-col"], table[class="table-col-border"] {
          float: none !important;
          width: 100% !important;
          padding-left: 0 !important;
          padding-right: 0 !important;
          table-layout: fixed;
        }
        td[class="table-col-td"] {
          width: 100% !important;
        }
        table[class="table-col-border"] + table[class="table-col-border"] {
          padding-top: 12px;
          margin-top: 12px;
          border-top: 1px solid #E8E8E8;
        }
        table[class="table-col"] + table[class="table-col"] {
          margin-top: 15px;
        }
        td[class="table-row-td"] {
          padding-left: 0 !important;
          padding-right: 0 !important;
        }
        table[class="navbar-row"] , td[class="navbar-row-td"] {
          width: 100% !important;
        }
        img {
          max-width: 100% !important;
          display: inline !important;
        }
        img[class="pull-right"] {
          float: right;
          margin-left: 11px;
          max-width: 125px !important;
          padding-bottom: 0 !important;
        }
        img[class="pull-left"] {
          float: left;
          margin-right: 11px;
          max-width: 125px !important;
          padding-bottom: 0 !important;
        }
        table[class="table-space"], table[class="header-row"] {
          float: none !important;
          width: 98% !important;
        }
        td[class="header-row-td"] {
          width: 100% !important;
        }
      }
      @media only screen and (max-width: 480px) {
        table[class="table-row"] {
          padding-left: 16px !important;
          padding-right: 16px !important;
        }
      }
      @media only screen and (max-width: 320px) {
        table[class="table-row"] {
          padding-left: 12px !important;
          padding-right: 12px !important;
        }
      }
      @media only screen and (max-width: 608px) {
        td[class="table-td-wrap"] {
          width: 100% !important;
        }
      }
      </style>
      </head>
      <body style="font-family: Arial, sans-serif; font-size:13px; color: #444444; min-height: 200px;" bgcolor="#E4E6E9" leftmargin="0" topmargin="0" marginheight="0" marginwidth="0">
      <table width="100%" height="100%" bgcolor="#E4E6E9" cellspacing="0" cellpadding="0" border="0">
      <tr><td width="100%" align="center" valign="top" bgcolor="#E4E6E9" style="background-color:#E4E6E9; min-height: 200px;">


      <table class="table-space" height="8" style="height: 8px; font-size: 0px; line-height: 0; width: 600px; background-color: #ffffff;" width="600" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="table-space-td" valign="middle" height="8" style="height: 8px; width: 600px; background-color: #ffffff;" width="600" bgcolor="#FFFFFF" align="left">&nbsp;</td></tr></tbody></table>

      <table class="table-row-fixed" width="600" bgcolor="#FFFFFF" style="table-layout: fixed; background-color: #ffffff;" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="table-row-fixed-td" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding-left: 24px; padding-right: 24px;" valign="top" align="left">
      <table class="table-col" align="left" width="285" style="padding-right: 18px; table-layout: fixed;" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="table-col-td" width="500" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal;" valign="top" align="left">
      <table class="header-row" width="500" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;"><tbody><tr><td class="header-row-td" width="500" style="font-size: 28px; margin: 0px; font-family: Arial, sans-serif; font-weight: normal; line-height: 19px; color: #478fca; padding-bottom: 10px; padding-top: 15px;" valign="top" align="left">'.$this->titulo.':</td></tr></tbody></table>
      <p style="margin: 0px; font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px;">
      Problemas com o script automático. Contatar equipe de suporte
      </p>
      <br>
      <table width="100%" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;"><tbody><tr><td width="100%" bgcolor="#f5f5f5" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding: 9px; border: 1px solid #e3e3e3; background-color: #f5f5f5;" valign="top" align="left"><b>'.$this->nome_funcao--.'</b>&nbsp;&nbsp;'.$this->saida.'</td></tr></tbody></table>
      <br>

      <br>
      </td></tr></tbody></table>
      </td></tr></tbody></table>

      <table class="table-space" height="32" style="height: 32px; font-size: 0px; line-height: 0; width: 600px; background-color: #ffffff;" width="600" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="table-space-td" valign="middle" height="32" style="height: 32px; width: 600px; padding-left: 18px; padding-right: 18px; background-color: #ffffff;" width="600" bgcolor="#FFFFFF" align="center">&nbsp;<table bgcolor="#E8E8E8" height="0" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td bgcolor="#E8E8E8" height="1" width="100%" style="height: 1px; font-size:0;" valign="top" align="left">&nbsp;</td></tr></tbody></table></td></tr></tbody></table>

      <table class="table-row" width="600" bgcolor="#FFFFFF" style="table-layout: fixed; background-color: #ffffff;" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="table-row-td" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding-left: 36px; padding-right: 36px;" valign="top" align="left">
      <table class="table-col" align="left" width="528" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;"><tbody><tr><td class="table-col-td" width="528" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal;" valign="top" align="left">
      <div style="font-family: Arial, sans-serif; line-height: 19px; color: #777777; font-size: 14px; text-align: center;">&copy; 2018 Grupo Erviegas</div>
      <table class="table-space" height="8" style="height: 8px; font-size: 0px; line-height: 0; width: 528px; background-color: #ffffff;" width="528" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="table-space-td" valign="middle" height="8" style="height: 8px; width: 528px; background-color: #ffffff;" width="528" bgcolor="#FFFFFF" align="left">&nbsp;</td></tr></tbody></table>
      </td></tr></tbody></table>
      </td></tr></tbody></table>
      <table class="table-space" height="14" style="height: 14px; font-size: 0px; line-height: 0; width: 600px; background-color: #ffffff;" width="600" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="table-space-td" valign="middle" height="14" style="height: 14px; width: 600px; background-color: #ffffff;" width="600" bgcolor="#FFFFFF" align="left">&nbsp;</td></tr></tbody></table>
      </td></tr>
      </table>
      </body>
      </html>
      ';
    }
  }
}

?>
