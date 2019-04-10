<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 26/03/2019
 * Time: 15:17
 */

namespace Sistema;


use \PDO;
use PDOException;

class Database
{
    private $database;
    private $db;

    function __construct()
    {
        $database = null;

        // Configurações do Banco de dados
        require("./app/config/database.php");

        // Adiciona as configurações ao item privado
        $this->database = $database;

        try
        {
            // Realiza a conexão do banco
            $this->db = new PDO('mysql:host='.$database["hostname"].';dbname='.$database["database"].'',
                ''.$database["username"].'',
                ''.$database["password"].'',
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));


            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }
        catch (\PDOException $e)
        {
            echo 'Error:'. $e->getMessage();
        }
    }



    public function getConexao()
    {
        return $this->db;
    }


}