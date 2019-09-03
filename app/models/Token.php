<?php
/**
 * =======================================================
 *
 *  Exemplo de Model a ser seguido pelo usuário,
 *  este simples exemplo já possui os métodos principais de
 *  um CRUD.
 *
 *  insert, update, get, delete
 *
 * =======================================================
 *
 * Autor: Igor Cacerez
 * Date: 04/04/2019
 * Time: 12:22
 *
 */

namespace Model;

use Sistema\Database;


class Token extends Database
{
    private $conexao;

    // Método construtor
    public function __construct()
    {
        // Carrega o construtor da class pai
        parent::__construct();

        // Retorna a conexao
        $this->conexao = parent::getConexao();

        // Seta o nome da tablea
        parent::setTable("token");

    } // END >> Fun::__construct()

} // END >> Class::Example