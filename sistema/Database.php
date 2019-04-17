<?php
/**
 * ===================================================================================
 *
 * Esta class é de extrema importancia para o frameWork
 * ele serve para facilitar a vida dos usuários, deixando um
 * CRUD pré programado para ser utilizado em todos os models.
 *
 * Essa classe não deve ser alterada de maneira alguma, pois qualquer mudança pode
 * causar erros irreversiveis.
 *
 * ===================================================================================
 *
 *  Autor: Igor Cacerez
 *  Data: 17/04/2019
 *
 */

namespace Sistema;


use \PDO;

class Database
{
    private static $database;
    private static $db;

    private static $table;



    function __construct()
    {
        $database = null;

        // Configurações do Banco de dados
        require("./app/config/database.php");

        // Adiciona as configurações ao item privado
        self::$database = $database;

        try
        {
            // Realiza a conexão do banco
            self::$db = new PDO('mysql:host='.$database["hostname"].';dbname='.$database["database"].'',
                ''.$database["username"].'',
                ''.$database["password"].'',
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));


            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }
        catch (\PDOException $e)
        {
            echo 'Error:'. $e->getMessage();
        }
    }





    // Método responsável por retornar a conexão
    // com o banco de dados
    public function getConexao()
    {
        return self::$db;
    }
    

    /**
     *  Métodos para facilitar o desenvolvimento de aplicações
     *  deixando um "CRUD" pré programado
     */


    // Seta a tabela
    public function setTable($table)
    {
        self::$table = $table;
    }




    // Retorna os dados do banco
    // Pode-se passar por parametro o where em forma de array ou string
    // order By e Limit
    public function get($where = null, $order = null, $limit = null)
    {
        // Busca
        $aux = null;

        // verifica se possui where
        if($where == null)
        {
            $sql = "SELECT * FROM " . self::$table;
        }
        else
        {
            // Verifica se é uma array
            if(is_array($where))
            {
                $sql = "SELECT * FROM " . self::$table . " WHERE ";
                $whereAux = null;

                foreach ($where as $item => $valor)
                {
                    if ($whereAux != null)
                    {
                        $whereAux .= " AND ";
                    }

                    $whereAux .= "{$item} = :{$item}";

                    $aux[":" . $item] = $valor;
                }
            }
            else
            {
                $sql = $where;
            }
        }


        // Verifica se possui ordem
        if($order != null)
        {
            $sql .= " ORDER BY {$order}";
        }


        // Verifica se possui Limit
        if($limit != null)
        {
            $sql .= " LIMIT {$limit}";
        }


        // Executa a ação
        try
        {
            if($aux == null)
            {
                $query = self::$db->query($sql);
            }
            else
            {
                $query = self::$db->prepare($sql);
                $query->execute($aux);
            }

            return $query;
        }
        catch (\PDOException $e)
        {
            echo "Erro: " . $e->getMessage();
            exit;
        }

    } // END >> Fun::get()



    // Altera os dados do banco
    // Pasando os dados a ser alterados por array
    // e o where por array ou string
    public function update($altera = null, $where = null)
    {
        $aux = null;
        $whereAux = null;
        $table = self::$table;

        // Verifica se o altera é != null
        if($altera != null)
        {
            // Verifica se é um array
            if(is_array($altera))
            {
                $sql = "UPDATE {$table} SET ";

                // Dados a ser alterados
                foreach (altera as $item => $valor)
                {
                    // Verifica se não é o primeiro
                    if($aux != null)
                    {
                        $sql .= ", ";
                    }

                    $sql .= "{$item} = :{$item}";

                    $aux[":" . $item] = $valor;
                }


                // Verifica se é array
                if(is_array($where))
                {
                    $sql .= " WHERE ";

                    // Dados do where
                    foreach ($where as $item => $valor)
                    {
                        if($whereAux != null)
                        {
                            $sql .= "AND ";
                        }


                        // Verifica se já possui esse item no array
                        if(array_key_exists(":{$item}",$aux))
                        {
                            $sql .= "{$item} = :{$item}2 ";
                            $aux[":" . $item . "2"] = $valor;
                        }
                        else
                        {
                            $sql .= "{$item} = :{$item} ";
                            $aux[":" . $item] = $valor;
                        }
                    }
                }
                else
                {
                    // Verifica se é != de null
                    if($where != null)
                    {
                        $sql .= " WHERE " . $where;
                    }
                }


                // Executa a alteração
                try
                {
                    $query = self::$db->prepare($sql);
                    $query->execute($aux);

                    // Retorna
                    return $query;
                }
                catch (\PDOException $e)
                {
                    "Error: " . $e->getMessage();
                    exit;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }

    } // END >> Fun::update()



    // Insere um item no banco de dados
    // Retorna o ID do item inserido
    // Passa os itens a ser inserido por parametro via array
    public function insert($salva = null)
    {
        $table = self::$table;

        if(is_array($salva))
        {
            try
            {
                $sql = "INSERT INTO {$table} ";
                $aux = null;
                $colunas = null;
                $valores = null;

                // Percorre os valores
                foreach ($salva as $item => $valor)
                {
                    // Verifica se não é a primeira passada
                    if($colunas != null)
                    {
                        $colunas .= ",";
                        $valores .= ",";
                    }

                    $colunas = "{$item}";
                    $valores = ":{$item}";

                    $aux[":" . $item] = $valor;
                }

                $sql .= "({$colunas}) VALUES ({$valores})";

                $query = self::$db->prepare($sql);
                $query->execute($aux);

                return $query->lastInsertId();
            }
            catch (\PDOException $e)
            {
                echo "Erro: " . $e->getMessage();
                exit;
            }
        }
        else
        {
            return false;
        }

    } // END >> Fun::insert()



    // Deleta um item do banco de dados
    public function delete($where = null)
    {
        $table = self::$table;

        // Verifica se é diferente de null
        if($where != null)
        {
            if(is_array($where))
            {
                $sql = "DELETE FROM {$table} WHERE ";
                $aux = null;
                $whereAux = null;

                foreach ($where as $item => $value)
                {
                    // Verifica se n é a primeira passada
                    if($whereAux != null)
                    {
                        $whereAux .= " AND ";
                    }

                    $whereAux .= "{$item} = :{$item}";

                    $aux[":" . $item] = $value;
                }

                $sql .= $whereAux;
            }
            else
            {
                $sql = $where;
            }


            // Executa a ação no banco
            try
            {

                if($aux == null)
                {
                    self::$db->exec($sql);
                }
                else
                {
                    $query = self::$db->prepare($sql);
                    $query->execute($aux);
                }

                return true;
            }
            catch (\PDOException $e)
            {
                "Erro: " . $e->getMessage();
                exit;
            }

        }
        else
        {
            return false;
        }

    } // END >> Fun::delete()

} // END >> Class::Database