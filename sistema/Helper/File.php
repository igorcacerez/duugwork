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
    private $extensaoPermitida = null;


    /**
     * @param array $ext
     */
    public function setExtensaoValida(array $ext)
    {
        $this->extensaoPermitida = $ext;
    }


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
     * Método responsável por retornar a extensão
     * do arquivo.
     * ----------------------------------------------
     * @return array|mixed|string
     */
    private function getExtensao()
    {
        // Arquivo
        $arquivo = $this->file;

        // Pega a extensão do arquivo
        $ext = explode(".",basename($arquivo['name']));
        $ext = end($ext);
        $ext = strtolower($ext);

        // Retorna a extensao
        return $ext;
    }


    /**
     * Método responsável por validar se o tamanho do
     * arquivo é permitido.
     * -----------------------------------------------
     * @return bool
     */
    public function validaSize()
    {
        // Recupera o arquivo
        $retorno = true;
        $arquivo = $this->file;
        $sizeMaximo = $this->maxSize;

        // Verifica se informou o size maximo
        if(!empty($sizeMaximo))
        {
            // Verifica se o arquivo possui é maior que o pemitido
            if($arquivo["size"] > $sizeMaximo)
            {
                // Avisa que deu merda
                $retorno = false;
            }
        }

        // Retorno
        return $retorno;

    } // End >> Fun::validaSize()



    /**
     * Método responsável por validar se o arquivo possui
     * um extensão permitida. Retornando true ou false.
     * ----------------------------------------------------
     * @return bool
     */
    public function validaExtensao()
    {
        // Variaveis
        $ext = $this->getExtensao();
        $permitido = $this->extensaoPermitida;
        $retorno = false;

        // Verifica se preencheu o permitido
        if($permitido != null)
        {
            // Percorre o array
            foreach ($permitido as $per)
            {
                // Deixa minusculo
                $per = strtolower($per);

                // Verifica se é igual
                if($per == $ext)
                {
                    // Ta Ok!
                    $retorno = true;

                    // Sai do loop
                    break;
                }
            }
        }
        else
        {
            // Ta ok!
            $retorno = true;
        } // Qualquer extensão é permitida.

        // Retorno
        return $retorno;

    } // End >> fun::validaExtensao()


    /**
     * Método responsável por comprimir uma deteminada imagem.
     * ----------------------------------------------------------------
     * @param $source_path - Imagem atual
     * @param $destination_path - Imagem a ser gerada
     * @param $quality - qualidade em porcentagem
     */
    public function compressImage($source_path, $destination_path, $quality = 50)
    {
        // Pega as informações da imagem
        $info = getimagesize($source_path);

        // Verifica a extensao
        if($info['mime'] == 'image/jpeg')
        {
            $image = imagecreatefromjpeg($source_path);
        }
        elseif($info['mime'] == 'image/png')
        {
            $image = imagecreatefrompng($source_path);
        }

        // Cria a imagem
        imagejpeg($image, $destination_path, $quality);

    } // End >> fun::compressImage()
    
    
    /**
     * Método responsável por processar o upload de arquivo
     * -------------------------------------------------------
     * @return bool|false|string|null
     */
    public function upload()
    {
        // Chama as variaveis
        $retorno = false;
        $nome    = $this->getName();
        $arquivo = $this->getFile();
        $caminho = $this->getStorange();

        // Verifica se o nome foi adicionado
        if($nome == null)
        {
            // como n foi o arquivo fica com a data atual
            $nome = date("Y-m-d-his");
        }

        // Pega a extensão do arquivo
        $ext = $this->getExtensao();

        // Verifica se possui uma extensão permitida
        if($this->validaExtensao())
        {
            // Verifica se o arquivo possui um tamanho permitido
            if($this->validaSize())
            {
                // Seta o nome do arquivo
                $nome .= "." . $ext;
                $caminho .= "/" . $nome;

                // faz o upload
                if(move_uploaded_file($arquivo['tmp_name'], $caminho))
                {
                    // retorna o nome do arquivo
                    $retorno = $nome;
                }
            }
        }

        // Return
        return $retorno;

    } // END >> Fun::upload()


} // End >> Fun::File()