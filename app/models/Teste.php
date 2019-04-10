<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 04/04/2019
 * Time: 12:22
 */

namespace Model;

use mysql_xdevapi\Exception;
use Sistema\Database;

use \PDO;
use PDOException;

class Teste extends Database
{
    // Retorna curso |Todos ou Especifico|
    public function get($slug = null)
    {
        try
        {
            $db = parent::getConexao();
        }
        catch (Exception $e)
        {
            echo $e->getMessage() . " - Code: " . $e->getCode();
            exit;
        }

    } // End::Fun >> get

}