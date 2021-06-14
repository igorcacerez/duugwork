# DuugWork

É um framework php que possibilita o desenvolvimento de aplicações de diferentes tamanhos, tendo como base o rápido processamento e baixissimo consumo de processamento, tendo o mínimo de arquivos possíveis. 

## Índice

[TOC]


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