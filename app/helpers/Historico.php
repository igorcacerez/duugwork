<?php

/**
 * Essa classe será responsável por tratar dos
 * salvamentos e restauração  de historico.
 */

// NameSpace
namespace Helper;


// Classe
class Historico
{
    // Objetos
    private $objModelHistorico;

    // Método construtor
    public function __construct()
    {
        // Instancia Objetos
        $this->objModelHistorico = new \Model\Historico();

    } // End >> fun::__construct()


    /**
     * Método responsável por inserir um histórico no banco de dados
     * verificando se os dados obrigatórios foram informados.
     * Retorna True ou false.
     * -------------------------------------------------------------
     * @param array $salva [id_login, tabela, chavePrimaria, acao, json]
     * @return bool
     */
    public function salva(array $salva)
    {
        // Variaveis
        $return = false;
        $obj = null;

        // Adiciona a data ao array
        $salva["data"] = date("Y-m-d H:i:s");

        // Verifica se informou os campos obrigatórios
        if(!empty($salva["id_usuario"]) &&
            !empty($salva["acao"]) &&
            !empty($salva["descricao"]))
        {
            // Insere
            $obj = $this->objModelHistorico->insert($salva);

            // Verifica se inseriu
            if($obj != null && $obj != false)
            {
                // Seta como inserido
                $return = true;
            }
        }

        // Retorno
        return $return;

    } // End >> fun::salva()

} // End >> Class::Helper\Historico