<?php
/**
 * ---------------------------------------
 * User: igorcacerez | github
 * Date: 02/09/2019
 * ---------------------------------------
 *
 * Classe responsável por realizar todos os processamentos
 * necessarios para upload de arquivos.
 *
 */

namespace Sistema\Helper;


class File
{
    // Variaveis
    private $file = null;
    private $storange = null;
    private $name = null;
    private $maxSize = null;


    /**
     * @return null
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @param null $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


    /**
     * @return null
     */
    public function getStorange()
    {
        return $this->storange;
    }


    /**
     * @param null $storange
     */
    public function setStorange($storange)
    {
        $this->storange = $storange;
    }


    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return null
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }


    /**
     * @param null $maxSize
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = $maxSize;
    }



    /**
     * ======================================
     *              Métodos
     * ======================================
     */


    /**
     * Método responsável por retornar o tamanho de um arquivo
     * --------------------------------------------------------
     * @param $file
     * @return mixed
     */
    public function sizeFile($file)
    {
        return $file["size"];
    } // End >> Fun::sizeFile()



    /**
     * Método responsável por processar o upload de arquivo
     * -------------------------------------------------------
     * @return bool|false|string|null
     */
    public function upload()
    {
        // Chama as variaveis
        $nome = $this->getName();
        $arquivo = $this->getFile();
        $caminho = $this->getStorange();
        $size = $this->getMaxSize();

        // Verifica se o nome foi adicionado
        if($nome == null)
        {
            // como n foi o arquivo fica com a data atual
            $nome = date("Y-m-d-his");
        }

        // Pega a extensão do arquivo
        $ext = explode(".",basename($arquivo['name']));
        $ext = end($ext);
        $ext = strtolower($ext);


        // verifica se o usuário passou limit de tamanho
        if($size != null)
        {
            // Verifica se é maior que o tamanho permitido
            if($arquivo["size"] > $size)
            {
                return false;
            }
        }


        // Seta o nome do arquivo
        $nome .= "." . $ext;
        $caminho .= "/" . $nome;


        // faz o upload
        if(move_uploaded_file($arquivo['tmp_name'], $caminho))
        {
            // retorna o nome do arquivo
            return $nome;
        }
        else
        {
            return false;
        }

    } // END >> Fun::upload()


} // End >> Fun::File()