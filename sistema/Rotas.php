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
    private $group = [];
    private $rotas = [];
    private $erros = [];


    public function group($nome, $rota, $class)
    {
        // Deixa o nome e a rota minusculos
        $rota = strtolower($rota);
        $nome = strtolower($nome);

        // Salva o grupo
        $this->group[$nome] = [
            "rota" => $rota,
            "class" => $class
        ];
    }


    public function onGroup($grupo, $tipo, $rota, $funcao)
    {
        // Deixa a rota minuscula e tipo maiusculo
        $rota = strtolower($rota);
        $grupo = strtolower($grupo);
        $tipo = strtoupper($tipo);

        // Verifica se possui rota
        if($rota != null && $rota != "")
        {
            // Configura a rota com o grupo
            $rota = $this->group[$grupo]["rota"] . "/" . $rota;
        }
        else
        {
            // Configura a rota com o grupo
            $rota = $this->group[$grupo]["rota"];
        }



        // Configura a rota
        $rota = substr($rota, 0, 1) !== '/' ? '/' . $rota : $rota;
        $rota = str_replace('{p}', '(\w+)', $rota);
        $rota = str_replace('/', '\/', $rota);
        $rota = '/^' . $rota . '$/';

        // Class
        $classe = $this->group[$grupo]["class"] . "::" . $funcao;

        // salva a rota
        $this->rotas[$tipo][$rota] = $this->confClass($classe);
    }



    public function on($tipo, $rota, $funcao)
    {
        // Deixa a rota minuscula e tipo maiusculo
        $rota = strtolower($rota);
        $tipo = strtoupper($tipo);

        if($rota != "")
        {
            // Configura a rota
            $rota = substr($rota, 0, 1) !== '/' ? '/' . $rota : $rota;
            $rota = str_replace('{p}', '(\w+)', $rota);
            $rota = str_replace('/', '\/', $rota);
            $rota = '/^' . $rota . '$/';
        }

        // salva a rota
        $this->rotas[$tipo][$rota] = $this->confClass($funcao);
    }


    public function onError($erro, $funcao)
    {
        // salva o erro
        $this->erros[$erro] = $this->confClass($funcao);
    }


    public function executar()
    {
        // Variaveis
        $dados = null;

        // Busca o tipo e url da requisição
        $tipo = $this->method();
        $url = $this->uri();

        $url = strtolower($url);

        // trata a url com / no final
        if(substr($url, -1) == "/")
        {
           $url = substr_replace($url, '', -1);
        }

        // Pega o achado
        $encontrado = $this->rotas[$tipo];

        foreach ($encontrado as $rota => $valor)
        {
            if($url == "" && $rota == "")
            {
                return $this->exibeMetodo($valor, []);
            }
            else
            {
                if(@preg_match($rota, $url, $parametros))
                {
                    // Parametros
                    array_shift($parametros);

                    return $this->exibeMetodo($valor, $parametros);
                }
            }
        }


        // Busca o erro 404
        $erro404 = $this->erros["404"];

        if(is_object($erro404) == false)
        {
            $erro404 = explode("::",$erro404);

            // Instancia a Classe
            $Class = new $erro404[0]();

            // Como não achou nada chama o 404
            return call_user_func_array([$Class, $erro404[1]], []);
        }
        else
        {
            // Como não achou nada chama o 404
            return call_user_func_array($erro404, []);
        }
    }






    /* -----------------------------------------
     *             MÉTODOS PRIVADOS
     * -----------------------------------------
     */


    private function exibeMetodo($valor, $parametros)
    {
        // Verifica se é função local
        if(is_object ($valor) == false)
        {
            // separa a class do metodo
            $valor = explode("::",$valor);

            // Instancia a Classe
            $Class = new $valor[0]();

            // Chama o método
            return call_user_func_array([$Class, $valor[1]], $parametros);
        }
        else
        {
            // Chama o método pois é local
            return call_user_func_array($valor, $parametros);
        }
    }


    private function confClass($class)
    {
        if(is_callable($class))
        {
            return $class;
        }
        else
        {
            // quebra em sub
            $sub = explode("\\", $class);

            // verifica se inseriu namespace
            if(isset($sub[1]) == false)
            {
                return "\Controller\\" . $class;
            }
            else
            {
                return $class;
            }

        }
    }


    private function method()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'cli';
    }


    private function uri()
    {
        $self = isset($_SERVER['PHP_SELF']) ? str_replace('index.php/', '', $_SERVER['PHP_SELF']) : '';
        $uri = isset($_SERVER['REQUEST_URI']) ? explode('?', $_SERVER['REQUEST_URI'])[0] : '';

        if ($self !== $uri) {
            $peaces = explode('/', $self);
            array_pop($peaces);
            $start = implode('/', $peaces);
            $search = '/' . preg_quote($start, '/') . '/';
            $uri = preg_replace($search, '', $uri, 1);
        }

        return $uri;
    }


} // END::Class >> Rotas