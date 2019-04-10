<?php
/**
 *  ===============================================================
 *                ATENÇÃO - CLASSE MUITO IMPORTANTE
 *  ===============================================================
 *
 *           Não Modifique essa classe em hipoteze alguma, 
 *          Quando programei isso apenas eu e Deus entendia. 
 *                        Agora, só Deus.
 * 
 *  ----------------------------------------------------------------
 * 
 *      Essa classe é responsável por realizar toda a lógica de 
 *      configuração das rotas do sistema.
 * 
 * */

namespace Sistema;

class Rotas
{
    private $url;
    private $rotas;

    
    function __construct()
    {
        $rotas = null;

        // A configuração das rotas
        require("app/config/rotas.php");

        // Pega a url
        if(isset($_GET["url"]))
        {
            $url = $_GET["url"];

            // Transforma a url em array e joga na varivel global
            $this->url = explode("/",$url);
        }

        // Salva as configurações na varivel global
        $this->rotas = $rotas;
    }




    public function configurar()
    {
        $url = $this->url;
        $rotas = $this->rotas;
        $retorno = null;

        // Verifica se é a página inicial
        if($url == null)
        {
            $retorno = self::configDefault($rotas);
        }
        else
        {
            // Verifica se a rota está configurada
            if(array_key_exists($url[0],$rotas))
            {
                $rotas = $rotas[$url[0]];
                $controller = "Controller\\" . $rotas["controller"];

                // Verifica se possui métodos
                if(isset($rotas['metodos']) && isset($url[1]))
                {
                    if(isset($rotas['metodos'][$url[1]]))
                    {
                        $rotas = $rotas["metodos"][$url[1]];

                        // Verifica se possui parametro
                        if($rotas["parametros"] != null && $rotas["parametros"] != 0)
                        {
                            // remove o controler e metodo da url
                            array_shift($url);
                            array_shift($url);

                            // Verifica se possui o numero de parametros  certo
                            if(count($url) == $rotas["parametros"])
                            {
                                $retorno = [
                                    "controller" => $controller,
                                    "metodo" => $rotas["metodo"],
                                    "parametros" => $url
                                ];
                            }
                            else
                            {
                                self::erro(404);
                            }
                        }
                        else
                        {
                            $retorno = [
                                "controller" => $controller,
                                "metodo" => $rotas["metodo"],
                                "parametros" => null
                            ];
                        }
                    }
                    else 
                    {
                        self::erro(404);
                    }
                }
                else
                {
                    if(isset($rotas['index']))
                    {

                        // Verifica se existe parametos
                        if(isset($rotas["parametros"]))
                        {
                            // remove o controler e metodo da url
                            array_shift($url);

                            // Verifica se possui o numero de parametros  certo
                            if(count($url) == $rotas["parametros"])
                            {
                                $paramentros = $url;
                            }
                            else
                            {
                                if(isset($rotas["erro404SemParametro"]) && $rotas["erro404SemParametro"] == false)
                                {
                                    $paramentros = null;
                                }
                                else 
                                {
                                    self::erro(404);
                                }
                            }
                        }
                        else
                        {
                            $paramentros = null;
                        }

                        $retorno = [
                            "controller" => $controller,
                            "metodo" => $rotas["index"],
                            "parametros" => $paramentros
                        ];
                    }
                    else
                    {
                        if(isset($rotas["erro404SemParametro"]) && $rotas["erro404SemParametro"] == false)
                        {
                            $retorno = [
                                "controller" => $controller,
                                "metodo" => $rotas["metodo"],
                                "parametros" => null
                            ];
                        }
                        else 
                        {
                            self::erro(404);
                        }
                    }
                }
            }
            else
            {
                self::erro(404);
            }
        }


        return $retorno;
    }




    static function configDefault($rotas = null)
    {
        $retorno = [
            "controller" => "Controller\\" . $rotas["default"]["controller"],
            "metodo" => $rotas["default"]["index"],
            "parametros" => null
        ];

        return $retorno;
    }



    static function erro($tipo)
    {
        $Obj = new Controller();

        // Exibe o erro
        $Obj->view("erro/{$tipo}");

        exit;
    }


} // END::Class >> Rotas