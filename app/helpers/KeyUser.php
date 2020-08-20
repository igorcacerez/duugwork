<?php

// NameSpace
namespace Helper;

// Importações
use Defuse\Crypto\Key;

// Classe
class KeyUser
{
    // Variaveis Globais
    private $caminho;
    private $arquivo;

    // Método construtor
    public function __construct()
    {
        // Instancia
        $this->caminho = "./storage/cliente/";
        $this->arquivo = "secret.key";

    } // End >> fun::__construct()


    /**
     * Método responsável por verificar se um usuário
     * possui uma key salva, caso tenha a retorno
     * caso contrario retorna false.
     * ----------------------------------------------
     * @param $usuario
     * @return bool|mixed
     * ----------------------------------------------
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function isKeyUser($usuario)
    {
        // Retorno false
        $retorno = false;

        // Criptografa o id
        $id = md5($usuario->id_usuario);

        // Forma o caminho
        $caminho = $this->caminho . $id . "/" . $this->arquivo;

        // Verifica se encontrou
        if(is_file($caminho))
        {
            // Abre a key
            $retorno = $this->abreKey($caminho);
        }

        // Retorno
        return $retorno;

    } // End >> fun::isKeyUser()


    /**
     * Método responsável por retornar uma key para um usuário
     * se ele tiver uma key ativa retorna ela, se não
     * cria uma e salva.
     * --------------------------------------------------------------
     * @param $usuario
     * @return bool|Key|mixed
     * --------------------------------------------------------------
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function createKeyUser($usuario)
    {
        // Verifica se já possui uma key
        $key = $this->isKeyUser($usuario);

        // Verifica
        if(empty($key))
        {

            // Gera a key
            $key = Key::createNewRandomKey();

            // Criptografa o id
            $id = md5($usuario->id_usuario);

            // Forma o caminho
            $caminho = $this->caminho . $id;

            if (!is_dir($caminho))
            {
                mkdir($caminho,0777, true);
            }

            $caminho = $caminho. "/" . $this->arquivo;

            // Salva a key no caminho
            $this->salvaKey($caminho, $key);
        }

        // Retorna
        return $key;
    } // End >> fun::createKeyUser()



    /**
     * Método responsável por salvar uma key
     * em um arquivo.
     * ----------------------------------------
     * @param $caminho
     * @param $key
     */
    private function salvaKey($caminho, $key)
    {
        // Repassa a key para string
        $salva = $key->saveToAsciiSafeString();

        // Cria o arquivo
        $arquivo = fopen($caminho,'w');

        // Salva a key no arquivo
        fwrite($arquivo, $salva);

        // Fecha o arquivo
        fclose($arquivo);

    } // End >> fun::salvaKey()


    /**
     * Método responsável por abrir um arquivo de key
     * existente.
     * -----------------------------------------------
     * @param $caminho
     * @return mixed
     * -----------------------------------------------
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    private function abreKey($caminho)
    {
        // Abre o arquivo
        $arquivo = fopen($caminho,'r');

        // Le o conteudo do arquivo aberto
        $conteudo = fread($arquivo, filesize($caminho));
        $conteudo = Key::loadFromAsciiSafeString($conteudo);

        // Fecha o arquivo
        fclose($arquivo);

        // Retorna o conteudo Descodificado
        return $conteudo;
    } // End >> fun::abreKey()

} // End >> Class::KeyUser