<?php

// Namespace da Classe
namespace Helper;

// Objetos Importados
use PHPMailer\PHPMailer\PHPMailer;

// Classe
class Email
{
    // Variaveis Globais
    private $PhpMailer;
    private $configuracoes;

    private $remetente = null;
    private $destinatarios = null;
    private $assunto = null;
    private $mensagem = null;


    // Método construtor
    public function __construct()
    {
        // Instancia os objetos
        $this->PhpMailer = new PHPMailer();

        // Configurações de envio
        $email = null;
        require("app/config/email.php");

        // Salva na global
        $this->configuracoes = $email;

    } // End >> Fun::__construct()

    // Setters ---

    /**
     * Método responsável por recuperar e armazenas os do
     * remetente do email.
     * ---------------------------------------------------------
     * @param $email
     * @param $nome
     */
    public function setRemetente($email, $nome)
    {
        // Salva a variavel
        $this->remetente = [
            "email" => $email,
            "nome" => $nome
        ];
    }


    /**
     * Método responspável por montar uma lista de destinatários
     * esse método pode ser chamado várias vezes.
     * ----------------------------------------------------------
     * @param $email
     * @param $nome
     */
    public function setDestinatario($email, $nome)
    {
        // Cria o array
        $aux = [
            "nome" => $nome,
            "email" => $email
        ];

        // Adiciona a global
        $this->destinatarios[] = $aux;
    }




    /**
     * Método responsável por recupearar e armazenar o
     * assunto do email.
     * ------------------------------------------------------
     * @param $assunto
     */
    public function setAssunto($assunto)
    {
        $this->assunto = $assunto;
    }



    /**
     * Método responsável por receber os dados da mensagem
     * e verificar se a mesma é texto simples ou template
     * ------------------------------------------------------
     * @param null $conteudo
     * @param null $data
     * @param bool $template
     * @throws \Exception
     */
    public function setMensagem($conteudo = null, $template = false)
    {
        // Verifica se é template
        if($template == false)
        {
            // O conteudo é texto simples
            $this->mensagem = $conteudo;
        }
        else
        {
            // Salva o conteudo
            $this->mensagem = file_get_contents($conteudo);
        }
    }




    /**
     * Método responsável por utilizar as informações
     * capturadas e realizar o disparo do email
     * ----------------------------------------------------
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send()
    {
        // Salva as configuracoes
        // --------------------------------------------------
        $this->PhpMailer->isSMTP(true);
        $this->PhpMailer->CharSet = $this->configuracoes["charset"];
        $this->PhpMailer->Host = $this->configuracoes["host"];
        $this->PhpMailer->Port = $this->configuracoes["porta"];
        $this->PhpMailer->SMTPAuth = true;
        $this->PhpMailer->SMTPSecure = $this->configuracoes["seguranca"];
        $this->PhpMailer->Username = $this->configuracoes["email"];
        $this->PhpMailer->Password = $this->configuracoes["senha"];
        $this->PhpMailer->isHTML(true);


        // Define o Assunto
        // --------------------------------------------------
        $this->PhpMailer->Subject = $this->assunto;


        // Define o Remetente
        // --------------------------------------------------
        $this->PhpMailer->From = $this->remetente["email"];
        $this->PhpMailer->FromName = $this->remetente["nome"];


        // Define os Destinatarios
        // --------------------------------------------------
        $destinatarios = $this->destinatarios;

        // Percorre todos os destinatarios incluidos
        foreach ($destinatarios as $dest)
        {
            // Adiciona o destinatario
            $this->PhpMailer->addBCC($dest["email"],$dest["nome"]);
        }


        // Define a Mensagem
        // --------------------------------------------------
        $this->PhpMailer->Body = $this->mensagem;

        // Envia o role
        // --------------------------------------------------
        return $this->PhpMailer->send();

    } // End >> Fun::send()

} // End >> Class::Helper\Email