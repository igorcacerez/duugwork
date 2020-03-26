<?php
/**
 * --------------------------------------
 * User: Igor
 * Date: 02/09/2019
 * --------------------------------------
 *
 * Método responsável por cuidar da segurança
 * e escalonagem de nivel de acesso de um determinado
 * usuário.
 *
 * Alem de contar sistema de token de acessos para usuarios logados.
 */

namespace Sistema\Helper;


use Model\Token;
use Model\Usuario;
use Sistema\Controller;

class Seguranca
{
    // Variaveis globais da class
    private $headerName = "Token"; # Nome do campo a ser validado no header
    private $key = "dflkf-fkljsn-7213665-dhja"; #chave para criptografia dos tokens
    private $validadeToken = 5; #tempo de validade dos tokens em horas

    // Objetos da Classe
    private $objModelToken = null;
    private $objModelUsuario = null;
    private $objHelperApi;


    // Método construtor
    public function __construct()
    {
        // Instancia o model
        $this->objModelToken = new Token();
        $this->objModelUsuario = new Usuario();
        $this->objController = new Controller();

    } // End >> Fun::__construct()



    /**
     * Método responsável por retornar os dados de autenticação do usuário
     * -------------------------------------------------------------------
     * @return array
     */
    public function getDadosLogin()
    {
        // Pega os dados do usuario
        $user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
        $pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

        // Verifica se informou os dados
        if($user == null || $pass == null)
        {
            // Acesso negado
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            die("Not authorized");
        }
        else
        {
            // Retorna os dados do usuario
            return [
                "usuario" => $user,
                "senha" => $pass
            ];
        }

    } // End >> Fun::getDadosLogin()




    /**
     * Método responsável por gerar um token para um determinado usuário
     * ou retornar um token ativo para o mesmo.
     * -------------------------------------------------------------------
     *
     * @param null $idUser
     * @return bool|mixed
     */
    public function getToken($idUser = null)
    {
        // Variaveis
        $dados = null;
        $salva = null;
        $Token = null;
        $novoToken  = null;
        $dataAtual = date("Y-m-d H:i:s");
        $dataExpira = date("Y-m-d H:i:s",strtotime("+{$this->validadeToken} hours"));

        // Busca um token ativo do usuário
        $Token = $this->objModelToken->get(["id_usuario" => $idUser, "data_expira >" => $dataAtual]);

        // Verifica se encontrou
        if($Token->rowCount() > 0)
        {
            // Retorna o token encontrado
            return $Token->fetch(\PDO::FETCH_OBJ);
        }
        else
        {
            // Gera um token
            $novoToken =  bin2hex(uniqid($this->key . "-" . $idUser . "-" . $dataAtual, true));

            // Array de inserção
            $salva = [
                "token" => $novoToken,
                "id_usuario" => $idUser,
                "ip" => $_SERVER["REMOTE_ADDR"],
                "data" => $dataAtual,
                "data_expira" => $dataExpira
            ];

            // Adiciona o token no banco
            $idToken = $this->objModelToken->insert($salva);

            // Verifica se adicionou
            if($idToken != false)
            {
                // Busca o item adicionado
                return $this->objModelToken->get(["id_token" => $idToken])->fetch(\PDO::FETCH_OBJ);
            }
            else
            {
                // Erro!
                return false;
            }
        }

    } // End >> Fun::getToken()




    /**
     * Método responsável por retornar o usuário atravez de um token
     * de login.
     * ---------------------------------------------------------------
     *
     * @param null $token
     * @return bool|mixed|null
     */
    public function getUser($token = null)
    {
        // Variaveis
        $usuario = null;
        $tokenObj = null;
        $dados = null;

        // Busca o token
        $tokenObj = $this->objModelToken->get(["token" => $token]);

        // Verifica se encontrou
        if($tokenObj->rowCount() > 0)
        {
            // Fetch
            $tokenObj = $tokenObj->fetch(\PDO::FETCH_OBJ);

            // Busca o usuário
            $usuario = $this->objModelUsuario->get(["id_usuario" => $tokenObj->id_usuario])->fetch(\PDO::FETCH_OBJ);

            // Retorna o usuario
            return $usuario;
        }
        else
        {
            // Erro - Não encontrado
            return false;
        }

    } // End >> Fun::getUser()


    /**
     * Método responsável por verifica se o um usuario está logado no sistema.
     * Caso ele não esteja mostra o erro 401.
     * ------------------------------------------------------------------------
     *
     * @return mixed|null
     */
    public function security()
    {
        // Variaveis
        $bearer = null;
        $usuario = null;
        $dataAtual = date("Y-m-d H:i:s");

        // Percorre o cabeçalho
        foreach (getallheaders() as $name => $value)
        {
            // Verifica se é o name que deseja
            if($name == $this->headerName)
            {
                // Expload o Bearer
                $bearer = explode("Bearer ",$value);
                $bearer = (isset($bearer[1])) ? $bearer[1] : null;

                // Verifica se possui session
                if(isset($_SESSION["token"]))
                {
                    // Verifica se é o mesmo token
                    if($_SESSION["token"]->token == $bearer)
                    {
                        // Verifica se está ativo
                        if($_SESSION["token"]->data_expira > $dataAtual)
                        {
                            // Retorna o usuario com o token
                            $usuario = $_SESSION["usuario"];
                            $usuario->token = $_SESSION["token"];

                            // retorna e encerra o codigo
                            return $usuario;
                        }
                    }
                }


                // -- Não possui o codigo na session
                // -- Então ira buscar o código no banco de dados


                // Busca o token no banco
                $token = $this->objModelToken->get(["token" => $bearer]);

                // verifica se encontrou
                if($token->rowCount() > 0)
                {
                    // Fetch Token
                    $token = $token->fetch(\PDO::FETCH_OBJ);

                    // verifica se o token está ativo
                    if($token->data_expira > $dataAtual)
                    {
                        //Busca o usuario
                        $usuario = $this->objModelUsuario->get(["id_usuario" => $token->id_usuario])->fetch(\PDO::FETCH_OBJ);

                        // Add o token ao usuario
                        $usuario->token = $token;

                        // retorna o usuário e encerra o código
                        return $usuario;
                    }
                    else
                    {
                        // Avisa que o token expirou
                        $this->objController->api(["mensagem" => "Token informado expirou", "code" => 401]);
                    }
                }
                else
                {
                    // Acesso negado
                    header('WWW-Authenticate: Basic realm="My Realm"');
                    header('HTTP/1.0 401 Unauthorized');
                    die("Not authorized");
                }
            }

        } // end::foreach (getallheaders() as $name => $value)

        // Se chegou aqui é pq não achou
        // Acesso negado
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        die("Not authorized");

    } // End >> fun::security()

} // End >> Class::Seguranca()