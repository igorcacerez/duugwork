<?php

// Faz o Include de classes
function __autoload($class)
{
    $url = null;

    // Transforma o namespace em array
    $aux = explode('\\', $class);

    // Verifica qual o caminho
    switch ($aux[0])
    {
        case "Sistema":
            $url = "./sistema/";
            break;

        case "Controller":
            $url = "./app/controllers/";
            break;

        case "Model":
            $url = "./app/models/";
            break;

        case "Helper":
            $url = "./app/helpers/";
            break;

        default: 
            $url = null;
            break;
    }

    
    // Inclui o Arquivo
    if($url != null)
    {
        include($url . end($aux) . '.php');
    }
} // End >> AUTOLOAD

