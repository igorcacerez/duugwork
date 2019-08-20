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
    private $database;
    private $db;

    private $table;



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





    // Método responsável por retornar a conexão
    // com o banco de dados
    public function getConexao()
    {
        return $this->db;
    }
    

    /**
     *  Métodos para facilitar o desenvolvimento de aplicações
     *  deixando um "CRUD" pré programado
     */


    // Seta a tabela
    public function setTable($table)
    {
        $this->table = $table;
    }

    public function getTable()
    {
        return $this->table;
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
            $sql = "SELECT * FROM " . $this->table;
        }
        else
        {
            // Verifica se é uma array
            if(is_array($where))
            {
                $sql = "SELECT * FROM " . $this->table . " WHERE ";
                $whereAux = null;
                $cont = 1;

                foreach ($where as $item => $valor)
                {
                    if ($whereAux != null)
                    {
                        $whereAux .= " AND ";
                    }

                    $whereAux .= "{$item} = :A{$cont}";

                    $aux[":A" . $cont] = $valor;

                    // Incrementa o cont
                    $cont++;
                }

                $sql .= $whereAux;
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
                $query = $this->db->query($sql);
            }
            else
            {
                $query = $this->db->prepare($sql);
                $query->execute($aux);
            }

            return $query;
        }
        catch (\PDOException $e)
        {
            echo "Erro: " . $e->getMessage() . " - " . $e->getFile() . " - " . $e->getLine();
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
        $table = $this->table;

        // Verifica se o altera é != null
        if($altera != null)
        {
            // Verifica se é um array
            if(is_array($altera))
            {
                $sql = "UPDATE {$table} SET ";

                // Dados a ser alterados
                foreach ($altera as $item => $valor)
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

                        $whereAux = 1;
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
                    $query = $this->db->prepare($sql);
                    $query->execute($aux);

                    // Retorna
                    return $query;

                }
                catch (\PDOException $e)
                {
                    echo "Error: " . $e->getMessage();
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
        $table = $this->table;

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

                    $colunas .= "{$item}";
                    $valores .= "?";

                    $aux[] .= $valor;
                }

                $sql .= "({$colunas}) VALUES ({$valores})";

                $query = $this->db->prepare($sql);
                $query->execute($aux);

                if($query != null && $query != false)
                {
                    return $this->db->lastInsertId();
                }
                else
                {
                    return false;
                }
            }
            catch (\PDOException $e)
            {
                echo "Erro: " . $e->getMessage() . " - " . $e->getFile() . " - " . $e->getLine();
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
        $table = $this->table;

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
                    $query = $this->db->exec($sql);
                }
                else
                {
                    $query = $this->db->prepare($sql);
                    $query->execute($aux);
                }

                return $query;
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

    } // END >> Fun::delete()

} // END >> Class::Database