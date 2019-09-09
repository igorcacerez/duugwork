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
 *  Autor: igorcacerez
 *  Data: 17/04/2019
 *
 */

namespace Sistema;


use \PDO;

class Database
{
    // Variaveis Globais da Class
    private $database;
    private $db;
    private $table;

    // Método Construtor
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

    } // End >> Fun::__construct()


    // Retorna a conexao
    public function getConexao()
    {
        return $this->db;
    }


    // Set Table
    public function setTable($table)
    {
        $this->table = $table;
    }


    // Get Table
    public function getTable()
    {
        return $this->table;
    }


    /**
     * Método responsável por execultar uma busca no banco de dados
     * e retornar os dados encontrados.
     * --------------------------------------------------------------
     *
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return bool|false|\PDOStatement
     */
    public function get($where = null, $order = null, $limit = null)
    {
        // Variaveis
        $aux = null;
        $whereAux = null;
        $cont = 1;

        // verifica se possui where
        if($where == null)
        {
            // Retorna tudo
            $sql = "SELECT * FROM " . $this->table;
        }
        else
        {
            // Verifica se é uma array
            if(is_array($where))
            {
                // Monta a query
                $sql = "SELECT * FROM " . $this->table . " WHERE ";

                // Percorre o array
                foreach ($where as $item => $valor)
                {
                    // Add o AND a query
                    $whereAux .= ($whereAux != null) ? " AND " : "";

                    // Pego o ultimo algarismo do item
                    $tipo = substr($item, -1);

                    // Verifica se é um verificador
                    if($tipo == "=" || $tipo == ">" || $tipo == "<")
                    {
                        // Adiciona a query sem o verificador
                        $whereAux .= "{$item} :A{$cont}";
                    }
                    else
                    {
                        // Adiciona a query com o verificador =
                        $whereAux .= "{$item} = :A{$cont}";
                    }

                    // Auxiliar para o bin
                    $aux[":A" . $cont] = $valor;

                    // Incrementa o cont
                    $cont++;

                } // End >> foreach($where as $item => $valor)

                // Adiciona o SQL
                $sql .= $whereAux;
            }
            else
            {
                // Fala que deu erro.
                return false;
            }
        }

        // Verifica se possui ordem
        if($order != null)
        {
            // Adiciona o SQL
            $sql .= " ORDER BY " . $order;
        }


        // Verifica se possui Limit
        if($limit != null)
        {
            // Adiciona o SQL
            $sql .= " LIMIT " . $limit;
        }


        // -- Executa a Ação
        try
        {
            // Verifica se é nullo
            if($aux == null)
            {
                // Execulta o sql
                $query = $this->db->query($sql);
            }
            else
            {
                // Prepara o SQL
                $query = $this->db->prepare($sql);

                // Informa os campos - Tratamento sql injection
                foreach ($aux as $item => $value)
                {
                    // Add o campo
                    $query->bindValue($item,$value);
                }

                // Execulta o sql
                $query->execute();
            }

            // retorna o que aconteceu
            return $query;
        }
        catch (\PDOException $e)
        {
            $this->getError($e);
        }

    } // END >> Fun::get()



    /**
     * Método responsável por editar os dados de um registro
     * no banco de dados.
     * ------------------------------------------------------
     *
     * @param null $altera
     * @param null $where
     * @return bool
     */
    public function update($altera = null, $where = null)
    {
        // Variaveis
        $aux = null;
        $whereAux = null;
        $table = $this->table;

        // Verifica se o altera é != null
        if($altera != null)
        {
            // Verifica se é um array
            if(is_array($altera))
            {
                // Monta o sql
                $sql = "UPDATE {$table} SET ";

                // Zera o contador
                $cont = 1;

                // Dados a ser alterados
                foreach ($altera as $item => $valor)
                {
                    // Verifica se não é o primeiro
                    $sql .= ($aux != null) ? ", " : "";

                    // Cria o sql
                    $sql .= "{$item} = :A{$cont}";

                    // itens do bin
                    $aux["A:" . $cont] = $valor;
                    $cont++;
                }


                // Verifica se é array
                if(is_array($where))
                {
                    $sql .= " WHERE ";

                    // Zera o cont
                    $cont = 1;

                    // Dados do where
                    foreach ($where as $item => $valor)
                    {
                        // Add o AND a query
                        $sql .= ($whereAux != null) ? " AND " : "";


                        // Pego o ultimo algarismo do item
                        $tipo = substr($item, -1);

                        // Verifica se é um verificador
                        if($tipo == "=" || $tipo == ">" || $tipo == "<")
                        {
                            // Adiciona a query sem o verificador
                            $sql .= "{$item} :B{$cont}";
                        }
                        else
                        {
                            // Adiciona a query com o verificador =
                            $sql .= "{$item} = :B{$cont}";
                        }

                        // Auxiliar para o bin
                        $aux[":B" . $cont] = $valor;

                        // Incrementa o cont
                        $whereAux = 1;
                        $cont++;
                    }
                }
                else
                {
                    // Verifica se é nullo
                    if($where != null)
                    {
                        // avisa que deu erro
                        return false;
                    }
                }


                // Executa a alteração
                try
                {
                    // Prepara o SQL
                    $query = $this->db->prepare($sql);

                    // Informa os campos - Tratamento sql injection
                    foreach ($aux as $item => $value)
                    {
                        // Add o campo
                        $query->bindValue($item,$value);
                    }

                    // Execulta o sql
                    $query->execute();
                }
                catch (\PDOException $e)
                {
                    $this->getError($e);
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



    /**
     * Método responsável por adiciona um registro no banco de dados
     * e retornar o Id do mesmo caso for execultado com sucesso.
     * --------------------------------------------------------------
     *
     * @param null $salva
     * @return bool|string
     */
    public function insert($salva = null)
    {
        // Variaveis
        $table = $this->table;
        $sql = "INSERT INTO {$table} ";
        $aux = null;
        $colunas = null;
        $valores = null;

        // verifica se é um array
        if(is_array($salva))
        {
            try
            {
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
                $this->getError($e);
            }
        }
        else
        {
            return false;
        }

    } // END >> Fun::insert()



    /**
     * Método responsável por deletar um registro do banco de dados.
     * E retornar o item deletado.
     * -----------------------------------------------------------------
     *
     * @param null $where
     * @return bool|\PDOStatement
     */
    public function delete($where = null)
    {
        // Variaveis
        $table = $this->table;
        $sql = "DELETE FROM {$table} WHERE ";
        $aux = null;
        $whereAux = null;
        $cont = 1;

        // Verifica se é diferente de null
        if($where != null)
        {
            // Verifica se é array
            if(is_array($where))
            {
                // Percorre o where
                foreach ($where as $item => $value)
                {
                    // Add o AND a query
                    $whereAux .= ($whereAux != null) ? " AND " : "";

                    // Pego o ultimo algarismo do item
                    $tipo = substr($item, -1);

                    // Verifica se é um verificador
                    if($tipo == "=" || $tipo == ">" || $tipo == "<")
                    {
                        // Adiciona a query sem o verificador
                        $whereAux .= "{$item} :A{$cont}";
                    }
                    else
                    {
                        // Adiciona a query com o verificador =
                        $whereAux .= "{$item} = :A{$cont}";
                    }

                    // Auxiliar para o bin
                    $aux[":A" . $cont] = $value;

                    // Incrementa o cont
                    $cont++;
                }

                $sql .= $whereAux;
            }
            else
            {
                return false;
            }


            // Executa a ação no banco
            try
            {
                // Prepara o SQL
                $query = $this->db->prepare($sql);

                // Seta os itens
                foreach ($aux as $item => $value)
                {
                    $query->bindValue($item,$value);
                }

                // Execulta o SQL
                $query->execute();

                // Retorna a execulção
                return $query;
            }
            catch (\PDOException $e)
            {
                // Retorna o erro
                $this->getError($e);
            }
        }
        else
        {
            return false;
        }

    } // END >> Fun::delete()




    /**
     * Método responsável por execultar uma query no banco de
     * dados.
     * ----------------------------------------------------
     *
     * @param null $sql
     * @param null|array $campos
     * @return bool|int|\PDOStatement
     */
    public function query($sql = null, $campos = null)
    {
        // Variaveis
        $tabela = $this->getTable();
        $cont = 1;
        $where = null;

        try
        {
            // Verifica se adicionou os campos
            if($campos != null)
            {
                // Verifica se é array
                if(is_array($campos))
                {
                    // Prepara o SQL
                    $query = $this->db->prepare($sql);

                    // Seta os itens
                    foreach ($campos as $item => $value)
                    {
                        $query->bindValue($item,$value);
                    }

                    // Execulta o SQL
                    $query->execute();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $query = $this->db->exec($sql);
            }

            // Retorna a query
            return $query;
        }
        catch (\PDOException $e)
        {
            $this->getError($e);
        }

    } // End >> fun::query()



    /**
     * ============================================
     *              Métodos Privados
     * ============================================
     */


    /**
     * Método responsável por retornar os erros em uma
     * tela diferenciada e bonita.
     * -----------------------------------------------
     * @param null $e
     */
    private function getError($e = null)
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

} // END >> Class::Database