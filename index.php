<?php

// Acesso a paginas externas
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization");

// Seta a zona time
date_default_timezone_set("America/Sao_Paulo");

// Globais
global $Rotas;
global $Conexao;

// Verifica se o composer está atualizado
if(is_file("./vendor/autoload.php") == true)
{
    // AutoLoad
    require("vendor/autoload.php");

    // Instancia a rota
    $Rotas = new DuugWork\Rotas();

    // Requires de configurações do sistema
    require("app/config/config.php");
    require("app/config/autoload.php");
    require("app/config/constantes.php");

    // Inclui os arquivos de rota
    $arquivos = $Rotas->autoload(["./app/config/rotas/"]);

    // Verifica se possui arquivos
    if(!empty($arquivos))
    {
        // Percorre os arquivos
        for ($i = 0; $i < count($arquivos); $i++)
        {
            // Inclui o arquivo de rotas
            require($arquivos[$i]);
        }
    }

    // Execulta a rota
    $Rotas->executar();
}
else
{
    // Seta um aviso que tem que rodar o composer
    echo "Rode o comando: composer update";
}



