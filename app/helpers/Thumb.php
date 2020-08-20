<?php

// NameSpace
namespace Helper;

// Importações
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

// Classe
class Thumb
{
    // Objetos
    private $file;
    private $width;
    private $height;
    private $caminho;
    private $nome = null;


    /**
     * Método responsável por armazenar
     * o arquivo a ser modificado.
     * --------------------------------------
     * @param $arquivo
     */
    public function setFile($arquivo)
    {
        // Add o arquivo a global
        $this->file = $arquivo;
    } // End >> fun::setFile()


    /**
     * Método responsável por armazenar
     * as informações de medida da imagem a
     * ser gerada.
     * ---------------------------------------
     * @param $w
     * @param $h
     */
    public function setTamanho($w, $h)
    {
        // Salva o tamanho nas globais
        $this->width = $w;
        $this->height = $h;

    } // End >> fun::setTamanho()


    /**
     * Método responsável por armazenar o
     * caminho da pasta onde a imagem
     * será salva.
     * ---------------------------------------
     * @param $caminho
     */
    public function setStorage($caminho)
    {
        // Salva a pasta onde a imagem será salva
        $this->caminho = $caminho;

    } // End >> fun::setStorage()


    /**
     * Método responsável por armazenar o
     * nome do arquivo a ser gerado.
     * ---------------------------------------
     * @param $nome
     */
    public function setNome($nome)
    {
        // Armazena o nome do arquivo
        $this->nome = $nome;
    } // End >> fun::setNome()



    /**
     * Método responsável por organizar as
     * informações e gerar a imagem
     * redimensionada.
     * ----------------------------------------------------------
     * @uses https://imagine.readthedocs.io/en/v0.2-0/image.html
     * ----------------------------------------------------------
     * @param $cortar bool [Informa qual metodo deve ser utilizado]
     * @return false|string|null
     */
    public function save($cortar = true)
    {
        // Variaveis
        $w = null;
        $h = null;
        $arquivo = null;
        $caminho = null;
        $nome = null;

        // Recupera as informações
        $w = $this->width;
        $h = $this->height;
        $arquivo = $this->file;
        $caminho = $this->caminho;

        // Configura o nome do arquivo
        $nome = $this->configuraNome();

        // Concatena o nome com o caminho
        $caminho = $caminho . $nome;

        // Instancia o objeto
        $ObjImagine = new Imagine();

        // Configurações
        $imagineSize = new Box($w,$h);

        // Verifica o modo
        if($cortar == true)
        {
            /*
             * Esse modo corta partes da imagem para que ela fique em um tamanho
             * proporcional ao $w e $h informado. Após cortar, diminui a imagem
             * de forma proporcional, lembrando que parte da imagem poderá ser perdida.
             */
            $imagineMode = ImageInterface::THUMBNAIL_OUTBOUND;
        }
        else
        {
            /*
             * Esse modo diminui a imagem de forma proporcional, utilizando
             * a medida $w e $h como limites maximos para a diminuição, não cortará
             * parte da imagem para deixar no tamanho informado.
             */
            $imagineMode = ImageInterface::THUMBNAIL_INSET;
        }

        // Faz o uplaod
        $ObjImagine->open($arquivo)
            ->thumbnail($imagineSize, $imagineMode)
            ->save($caminho);

        // Retorno o nome do arquivo
        return $nome;

    } // End >> fun::save()


    /**
     * Método responsável por configurar o nome da
     * imagem a ser salva.
     * ----------------------------------------------
     * @return false|string|null
     */
    private function configuraNome()
    {
        // Recupera o arquivo
        $arquivo = $this->file;
        $nome = $this->nome;

        // Verifica se não informou o nome
        if(empty($nome))
        {
            // Configura o nome atraves da data
            $nome = date("Y-m-d-his");

            // Pega a extensão do arquivo
            $ext = explode(".",basename($arquivo['name']));
            $ext = end($ext);
            $ext = strtolower($ext);

            // Junta o nome com a extensao
            $nome = $nome . "." . $ext;
        }

        // Retorna o nome do arquivo
        return $nome;

    } // End >> fun::configuraNome()

} // End >> Class::Helper\Thumb