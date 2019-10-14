<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 14/10/2019
 * Time: 14:43
 */

namespace Sistema;


class Conexao
{
    // Variaveis
    private $database;


    // Método construtor
    public function __construct()
    {
        // Variaveis
        $database = null;

        // Inclui os arquivos
        require("app/config/database.php");

        // Add os dados do banco a variavel
        $this->database = $database;

    } // End >> Fun::__construct()


    /**
     * Método responsável por verificar se já existe uma
     * conexão aberta e retornar a mesma caso existe, se
     * não existir, abre uma conexão com o banco e a retorna.
     * --------------------------------------------------
     * @return \PDO
     */
    public function getConexao()
    {
        // Chama a global
        global $Conexao;

        // Variaveis
        $con = null;

        // Verifica se a coneção foi realizada
        if($Conexao == null)
        {
            // Realiza a conexao
            $con = $this->conectar();

            // Salva na global
            $Conexao = $con;
        }

        // Retorna a conexão com o banco de dados
        return $Conexao;

    } // Enc >> Fun::getConexao();



    /**
     * Método responsável por realizar um conexão com o
     * banco de dados e retorna o objeto da mesma.
     * -------------------------------------------------
     * @return \PDO
     */
    private function conectar()
    {
        // Pega os dados do banco
        $database = $this->database;

        try
        {
            // Realiza a conexão com o banco de dados
            $db = new \PDO('mysql:host='.$database["hostname"].';dbname='.$database["database"].'',
                ''.$database["username"].'',
                ''.$database["password"].'',
                array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));


            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(\PDO::ATTR_ORACLE_NULLS, \PDO::NULL_EMPTY_STRING);

            // Salva a conexão com na global
            return $db;
        }
        catch (\PDOException $e)
        {
            // Exibe o erro
            $this->getError($e);
        }

    } // End >> Fun::conectar()



    /**
     * Método responsável por retornar os erros em uma
     * tela diferenciada e bonita.
     * -----------------------------------------------
     * @param null $e
     */
    public function getError($e = null)
    {
        // Variaveis para a view
        $dados = [
            "titulo" => "Erro na Database",
            "arquivo" => $e->getFile(),
            "descricao" => $e->getMessage(),
            "codigo" =>  $e->getCode(),
            "linha" => $e->getLine(),
        ];

        // Extrai as variaveis
        extract($dados,EXTR_OVERWRITE);

        // Chama a view
        include("./app/views/error/error.php");

        // Mata o código
        exit;

    } // End >> fun::getError()

} // End >> Class::Conexao()