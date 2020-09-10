<?php
/**
 * ------------------------------
 * User: igorcacerez
 * Date: 02/09/2019
 * ------------------------------
 *
 * Classe desenvolvido para auxiliar no trabalho com as requisições
 * Http. Organizando de maneira simples os envios recebidos.
 *
 */

namespace Sistema\Helper;


class Input
{
    // Variaveis
    private $varDelete = null;
    private $varPut = null;
    private $varPost = null;
    private $varGet = null;


    // Método construtor
    public function __construct()
    {
        // Verifica e armazena os dados recebidos via http
        $this->getInputsType();

    } // End >> Fun::__construct()


    /**
     * Método responsável por retornar os itens
     * Delete.
     * -----------------------------------------
     * @param $id
     * @return mixed
     */
    public function delete($id = null)
    {
        // Verifica se passou parametro
        return ($id == null) ? $this->varDelete : $this->varDelete[$id];

    } // End >> fun::delete()



    /**
     * Método responsável por retornar os dados
     * do tipo PUT
     * -------------------------------------------
     *
     * @param null $id
     * @return null
     */
    public function put($id = null)
    {
        // Verifica se passou parametro
        return ($id == null) ? $this->varPut : $this->varPut[$id];

    } // End >> fun::put()



    /**
     * Método responsável por retornar os dados
     * do tipo GET
     * -------------------------------------------
     *
     * @param null $id
     * @return null
     */
    public function get($id = null)
    {
        // Verifica se passou parametro
        return ($id == null) ? $this->varGet : $this->varGet[$id];

    } // End >> fun::get()



    /**
     * Método responsável por retornar os dados do tipo
     * Post
     * ------------------------------------------------
     *
     * @param null $id
     * @return null
     */
    public function post($id = null)
    {
        // Verifica se passou parametro
        return ($id == null) ? $this->varPost : $this->varPost[$id];

    } // End >> fun::post()



    /**
     * ========================================================
     *                   Métodos Privados
     * ========================================================
     */


    /**
     * Método responsável por verificar se possui o recebimento de alguma
     * informação atravez de protocolos HTTP.
     */
    private function getInputsType()
    {
        // Armazena os dados post
        $this->varPost = $_POST;

        // armazena os dados get
        $this->varGet = $_GET;

        // Verifica se ouve envio de dados via DELETE
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE'))
        {
            // responsável por armazenar os dados DELETE
            parse_str(file_get_contents('php://input'), $this->varDelete);
        }

        // Verifica se ouve envio de dados via PUT
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT'))
        {
            // Pega os dados json put
            $decoded_input = json_decode(file_get_contents("php://input"), true);

            // Percorre os dados
            foreach ($decoded_input as $dec => $value)
            {
                // Adiciona no array
                $this->varPut[$dec] = $value;
            }
        }

    } // End >> Fun::carregaDados()

} // End >> Class::Input()