# DuugWork

É um framework php que possibilita o desenvolvimento de aplicações de diferentes tamanhos, tendo como base o rápido processamento e baixissimo consumo de processamento, tendo o mínimo de arquivos possíveis. 

### Instalação

Para utilizar a estrutura é necessário que tenha o composer instalador. Após clonar esse diretório é necessário rodar o comando `composer update` para que todas as dependencias sejam instaladas. 

#### Configuração

- Config.php

	 Dentro da da pasta "app/config" estão os arquivo de configuração. O arquivo config.php é o responsável pela configuração geral do framework. 
	 
	 Neste arquivo você configura a url padrão do seu sistema
		// URL base do site.
		defined('BASE_URL') OR define('BASE_URL', '');

		// URL base do storange
		defined('BASE_STORAGE') OR define('BASE_STORAGE', '');

	 Caso a url padrão seja http://localhot/meu-produto/ defina desta maneira: 
	 
		 // URL base do site.
		defined('BASE_URL') OR define('BASE_URL', 'http://localhot/meu-produto/');


- Constantes.php

	 Neste arquivo você poderá definir constantes que serão utilizadas em todo o sistema.

- Database.php

	 Neste arquivo você configurará as informações de conexão com o banco de dados. 

	 	$database = [
			'hostname' => 'HOST',
			'username' => 'USUARIO',
			'password' => 'SENHA',
			'database' => 'BANCO DE DADOS'
		];

- Email.php 

	 Neste arquivo configure as informações de envio de email.
	 

#### Rotas

Dentro da pasta "app/config" terá outra pasta chamada "rotas", dentro dela você poderá criar quantos arquivos precisar com o nome que quiser. Dentro desses arquivos derá conter a regra de rotas. 

Para configurar uma rota, basta utilizar o seguinte código:

	$Rotas->on("METODO HTTP (GET,POST,PUT...)","URL","CONTROLLER::MÉTODO CORRESPONDENTE");

Exemplo: Você possui a url "produtos/listar" que quando acessada via GET deve ser chamado o método listarProdutos que está dentro da controller Produto. Você deve configurar desta maneira: 

	$Rotas->on("GET","produtos/listar","Produto::listarProdutos");

É possível criar grupos de rotas para que sejam declaradas de forma mais rápida.  É assim que você cria um grupo.

	$Rotas->group("NOME DO GRUPO","ROTA FIXA","CONTROLLER");

Para criar uma rota utilizando um grupo você cria desta maneira: 

	$Rotas->onGroup("NOME DO GRUPO","METODO HTTP","ROTA","METODO");

Caso fossemos criar a mesma rota feita acima só que utilizando um grupo, ela ficaria dessa forma:

	$Rotas->group("grupo-produtos","produtos","Produto");
	$Rotas->onGroup("grupo-produtos","GET","listar","listarProdutos");

### Controllers

As Constrollers são responsáveis por todos o processamento do sistema. Você deverá criar suas Classes Controllers dentro da seguinte pasta "app/controllers".

Exemplo da estrutura de uma controller

	namespace Controller;
	
	use DuugWork\Controller as CI_controller;

	class Principal extends CI_controller
	{

    	// Método construtor
    	function __construct()
    	{
        	// Carrega o contrutor da classe pai
        	parent::__construct();
    	}

    	public function index()
    	{
        	echo "Hello World";
    	}
		
	} // END::Class Principal

Para chamar uma view você utiliza dentro da controller o seguinte metodo: 

	$this->view("LOCAL DO ARQUIVO PHP", "ARRAY DE VARIAVEIS");

Exemplo: Se você possui o arquivo index.php dentro da pasta views você pode execultar ele dessa maneira: 

	$this->view("index");

Não é necessário informar as pastas antes da "app/views" e nem a extensão .php do arquivo. 

Caso a sua view seja assim: 

	 <h1><?= $ola; ?></h1>

Você deverá desenvolver seu método desta maneira, para que a váriavel $ola seja válida.

	 // Variavel
	 $texto = "Hello World";
	
	// Array de variaveis que deve ser exibida na view
	$dados = ["ola" => $texto];
	
	// Chama a view
	$this->view("index", $dados);


### Models

Os models são responsaveis por realizar todo o processo que utiliza banco de dados, tais como Insert, Update, delete... 

O DuugWork possui um sistema de pre-desenvolvimento, onde já temos os métodos de CRUD desenvolvidos. Então dessa forma seus models ficaram dessa forma: 

	namespace Model;

	use DuugWork\Database;

	class Usuario extends Database
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
        	parent::setTable("NOME DA TABELA NO BANCO DE DADOS");

    	} // END >> Fun::__construct()

	} // END >> Class::Example

Dessa forma os métodos padrões já estaram disponiveis, sendo necessário apenas o desenvolvimento de métodos especificos do seu sistema. 

- Get 
	
	Para realizar uma busca no banco utlizando o model, você fará desta maneira: 
	
		// Instancia o objeto Model
		$UsuarioModel = new \Model\Usuario();
		
		// Lista todos os objetos 
		$UsuarioModel
			->get()
			->fetchAll(\PDO::FETCH_OBJ); // Métodos Padrões do PDO

	Com o método get você poderá utilizar essas configurações como paramentro: 

		$UsuarioModel
			->get("ARRAY DE WEHRE", "ORDEM DE EXIBICAO", "LIMITE", "CAMPOS", "GROUP BY")

	 Exemplo de utilização: Queremos buscar todos os campos dos 10 ultimos usuários cadastrados cujo a o nome seja José:
	 
		$UsuarioModel
			->get(["nome" => "José"], "id DESC", "10", "*", null)