<?php

// Acesso a paginas externas
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization");

// Seta a zona time
date_default_timezone_set("America/Sao_Paulo");

// Globais
global $Rotas;

// AutoLoad
require("sistema/Helper/autoload.php");

// Objetos de configuração do sistema
use Sistema\Rotas;

// Instancia a rota
$Rotas = new Rotas();

// Requires de configurações do sistema
require("app/config/config.php");
require("app/config/constantes.php");
require("app/config/rotas.php");


// Verifica se existe o autoload do composer
if(is_file("./vendor/autoload.php") == true)
{
    // Chama o arquivo
    require './vendor/autoload.php';
}


// Execulta a rota
$Rotas->executar();

