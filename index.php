<?php 

// Objetos de configuração do sistema
use Sistema\Rotas;

// Requires de configurações do sistema
require("app/config/config.php");
require("app/config/constantes.php");

// AutoLoad
require("sistema/helper/autoload.php");


// Configura a rotas
$ObjRotas = new Rotas();
$RetornoRota = $ObjRotas->configurar();

$metodo =  $RetornoRota["metodo"];

// Instacia o controller
$Class = new $RetornoRota["controller"];

// Chama o método
$Class->$metodo($RetornoRota["parametros"]);

