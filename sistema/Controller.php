<?php
/**
 * ============================================================
 *
 *  Class de configuração do framework, esta classe só deve ser
 *  alterada em ultimo caso.
 *
 * ============================================================
 *
 * Autor: Igor Cacerez
 * Date: 26/03/2019
 * Time: 17:31
 *
 */

namespace Sistema;


class Controller
{

    private $vars = null;
    private $delete = null;
    private $put = null;

    // Método construtor
    function __construct()
    {
        // Start Session
        if(OPEN_SESSION == true)
        {
            session_start();
        }

        // responsável por armazenar os dados DELETE
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE')) {
            parse_str(file_get_contents('php://input'), $this->delete);
        }

        // responsável por armazenar os dados PUT
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
            parse_str(file_get_contents('php://input'), $this->put);
        }
    }


    // Método para exibição de páginas VIEW
    public function view($view = null, $dados = null)
    {
        // Verifica se o parametro dado é != de nulo
        if($dados != null)
        {
            foreach($dados as $var_name => $var_value)
            {
                $this->vars[$var_name] = $var_value;
            }
        }

        // Verifica se possui variaveis
        if($this->vars != null)
        {
            extract($this->vars,EXTR_OVERWRITE);
        }


        //Exibe a View
        include("./app/views/" . $view . ".php");

    } // END >> Fun::view()




    // Método responsável por configurar o retorno da api em json
    public function retornoAPI($dados = null)
    {
        header("Content-type: application/json; charset=utf-8");

        // Verifica se não informou o erro
        if(!isset($dados["tipo"]))
        {
            $dados["tipo"] = false;
        }

        // Verifica se informou a mensagem
        if(!isset($dados["mensagem"]))
        {
            $dados["mensagem"] = null;
        }


        // Verifica se não informou o data
        if(!isset($dados["objeto"]))
        {
            $dados["objeto"] = null;
        }

        // exibe
        echo json_encode($dados);

        // Mata o processamento
        exit;

    } // END >> Fun::retornoAPI()





    public function inputDelete($id)
    {
        return $this->delete[$id];
    }


    public function inputPut($id)
    {
        return $this->put[$id];
    }


    /**
     * ------------------------------------------------------
     *
     * Métodos que facilitam o desenvolvimento de sistemas.
     * Diversos métodos personalizados de rotinas básicas
     * que praticamente todo siststema utiliza e necessita.
     *
     * ------------------------------------------------------
     *
     * Esses métodos são editaveis e não influencia diretamente no
     * funcionamento do famework
     *
     * -------------------------------------------------------
     */


    // Método responsável por realizar upload de arquivos
    public function uploadFile($arquivo = null, $caminho = null, $nome = null, $extensao = null,  $size = null)
    {
        // Verifica se o nome foi adicionado
        if($nome == null)
        {
            $nome = date("Y-m-d-his");
        }

        // Pega a extensão
        $ext = explode(".",basename($arquivo['name']));
        $ext = end($ext);
        $ext = strtolower($ext);

        // verifica se o usuário passou restrição de extensão
        if($extensao != null)
        {
            // transforma em array
            $extensao = explode("|", $extensao);

            if(!in_array($ext, $extensao))
            {
                return false;
            }
        }


        // verifica se o usuário passou limit de tamanho
        if($size != null)
        {
            if($arquivo["size"] > $size)
            {
                return false;
            }
        }



        // Seta o nome do arquivo
        $nome .= "." . $ext;
        $caminho .= "/" . $nome;


        // faz o upload
        if(move_uploaded_file($arquivo['tmp_name'], $caminho))
        {
            return $nome;
        }
        else
        {
            return false;
        }

    } // END >> Fun::uploadFile()




    // Método responsável por formatar um numero na casa do milhar, deixando
    // as siglas na frente: K,M,B,T,Q
    public function formatNumero($numero = null)
    {
        // Variaveis
        $cont = 0;
        $array  = ["","K","M","B","T","Q"];

        // Divide o numero por mil
        while ($numero >= 1000)
        {
            $numero = $numero / 1000;
            $cont++;
        }


        // Verifica se o numero não é inteiro
        if(is_int($numero) == false)
        {
            // Deixa com duas casas decimais
            $numero = number_format($numero,2,".");
        }

        // Retorna o numero com a letra
        return $numero . $array[$cont];
    }


    // Debug 
    public function debug($item = null, $tipo = "array")
    {
        if($tipo == "array")
        {
            echo "<pre>" . $item . "</pre>";
        }
        else 
        {
            header("Content-type: application/json; charset=utf-8");
            echo json_encode($item);
        }

        exit;
    }


} // END >> Class::Controller