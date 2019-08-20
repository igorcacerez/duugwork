<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 24/04/2019
 * Time: 09:20
 */

namespace Sistema\Helper;

class Email
{
    private $remetente = null;
    private $destinatarios = null;
    private $configuracoes = null;
    private $assunto = null;
    private $mensagem = null;
    private $anexo = null;


    function __construct()
    {
        // Include os arquivos do php mailer
        require_once("./sistema/Helper/phpmailer/class.phpmailer.php");
        require_once("./sistema/Helper/phpmailer/class.smtp.php");
        require_once("./sistema/Helper/phpmailer/class.pop3.php");
        require_once("./sistema/Helper/phpmailer/class.phpmaileroauthgoogle.php");
        require_once("./sistema/Helper/phpmailer/class.phpmaileroauth.php");
    }



    public function enviaEmail()
    {
        $cont = 0;

        // Inicia a classe PHPMailer
        $mail = new \PHPMailer();

        // Define os dados do servidor e tipo de conexão
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(true); // Define que a mensagem será SMTP
        $mail->Host = $this->configuracoes["host"]; // Endereço do servidor SMTP
        $mail->Port = $this->configuracoes["port"]; // Porta



        // Verifica se ira utilizar autenticação de email
        if($this->configuracoes["autenticacao"] == true)
        {
            $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
            $mail->SMTPSecure = $this->configuracoes["seguranca"];
            $mail->Username = $this->configuracoes["email"];
            $mail->Password = $this->configuracoes["senha"];

            //$mail->Username = 'mail@desigual.com.br'; // Usuário do servidor SMTP
            //$mail->Password = 'Desigu@al#!147'; // Senha do servidor SMTP
        }



        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = $this->remetente["email"]; // Seu e-mail
        $mail->FromName = $this->remetente["nome"]; // Seu nome


        // Charset UTF-8
        $mail->CharSet = $this->configuracoes["charset"];


        // Define os destinatário(s)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        foreach ($this->destinatarios as $dest)
        {
            if($cont == 0)
            {
                $mail->AddAddress($dest[0], $dest[1]);
            }
            else
            {
                $mail->AddCC($dest[0], $dest[1]); // Copia
            }

            $cont++;
        }



        // Define os dados técnicos da Mensagem
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML


        //$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
        // Define a mensagem (Texto e Assunto)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->Subject = $this->assunto; // Assunto da mensagem



        // Mensagem a ser enviada
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->Body = $this->mensagem;


        // $mail->AltBody = "Este é o corpo da mensagem de teste, em Texto Plano! \r\n :)";
        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        if($this->anexo != null)
        {
            foreach($this->anexo as $anexo)
            {
                $mail->AddAttachment($anexo[0], $anexo[1]);  // Insere um anexo
            }
        }


        // Envia o e-mail
        $enviado = $mail->Send();


        // Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();


        return $enviado;
    }




    /**
     * --------------------------------
     *              SETERS
     * --------------------------------
     */


    public function setMensagem($mensagem, $variaveis = null, $template = false)
    {
        // Verifica se é um template
        if($template == false)
        {
            // Não é template então salva o conteudo enviado
            $this->mensagem = $mensagem;
        }
        else
        {
            // Pega o conteudo html do template informado
            $body = file_get_contents("./app/views/" . $mensagem . ".php");

            // Verifica se passou variaveis
            if(isset($variaveis))
            {
                // Percorre todas as variaveis informadas
                foreach($variaveis as $k=>$v)
                {
                    // Substitui elas pelo conteudo informado
                    $body = str_replace('{'.strtoupper($k).'}', $v, $body);
                }
            }

            // Adiciona o template a mensagem a ser enviada
            $this->mensagem = $body;
        }

    } // END >> Fun::setMensagem()


    public function setDestinatarios($destinatarios)
    {
        $this->destinatarios = $destinatarios;
    } // END >> Fun::setDestinatarios()


    public function setConfiguracoes($configuracoes)
    {
        $this->configuracoes = $configuracoes;
    } // END >> Fun::setConfiguracoes()


    public function setRemetente($remetente)
    {
        $this->remetente = $remetente;
    } // END >> Fun::setRemetente()


    public function setAnexo($anexo)
    {
        $this->anexo = $anexo;
    } // END >> Fun::setAnexo()


    public function setAssunto($assunto)
    {
        $this->assunto = $assunto;

    } // END >> Fun::setAssunto()


} // END >> Class::Email