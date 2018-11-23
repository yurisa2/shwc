<?php

define("SETTINGS_PRICE_MULTIPLICATION",$ajuste_preco_multiplicacao);
define("SUFFIX_PROD",$sufixo_prod);
define("PREFFIX_PROD",$prefixo_prod);
define("BRAND",$marca);
define("SETTINGS_PRICE_ADDITION",$ajuste_preco_soma);


//KEY TO SINCRONIZATION OF PRODUCTS AND ORDER
define("TITLE",$titulo);
define("PRICE",$preco);
define("DESCRIPTION",$descricao);
define("STOCK",$estoque);
define("IMAGES",$imagens);
define("ORDER",$pedido);

define("VERIFY_NFE",$verifica_nfe);

//MANDAR EMAIL
define("CONFIGMAIL",$configmail);     // true para habilitar o envio de email
define("EMAIL_TO",$email_destinatario);
define("EMAIL_ADDENVOICE",$email_nfe);
define("EMAIL_NEWSALE",$email_novacompra);
// Ainda há problemas não encontrados para o uso do Sendmail
define("SMTP",$SMTP);     //if SMTP equals false, sendmail will be used

//if SMTP equals true, the variaveis below will need be set up
define("HOST",$Host);     // Specify main and backup SMTP servers
define("SMTPAUTH",$SMTPAuth);     // Enable SMTP authentication
define("USERNAME",$Username);     // SMTP username
define("PASSWORD",$Password);     // SMTP password
define("SMTPSECURE",$SMTPSecure);     // Enable TLS encryption, `ssl` also accepted
