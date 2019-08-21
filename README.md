# framework-php MVC
Framework MVC simples, desenvolvido em PHP. 
facilita o desenvolvimento de pequenas e médias aplicações em MVC


[TOC]

### Estrutura

- App (Aqui fica salvo todos os códigos do seu sistema);
- Arquivos (Arquivos de estrura, tipo css, js, imagens);
- Sistema (Arquivos responsável pelo funcionamento do framework);

### Pastas

    app/
       config/
	   controllers/
	   helpers/
	   librarys/
	   models/
	   views/


#### Config

    config/
       config.php
	   constantes.php
	   database.php
	   rotas.php

##### config.php

Arquivo responsável por configurar seu sistema.

```php
// URL base do site.
// Aqui você define qual a url base do seu site. Ex: http://localhost/
defined('BASE_URL') OR define('BASE_URL', 'http://localhost/');


// Session | Caso deseje que a session seja iniciada em todas as páginas
// Apenas mude a constante para true.
defined("OPEN_SESSION") OR define('OPEN_SESSION', true);
```

##### constantes.php

Neste arquivo você armazena todas as contantes que deseja que sejam acessadas em todas as páginas do seu sistema.


##### database.php

Arquivo de configuração com o banco de dados

```php
// Nessa Array você irá informar os dados de acesso 
// ao banco de dados MySQL
$database = [
    'hostname' => 'localhost', //Host do banco
	'username' => 'root', //Usuario
	'password' => '', //Senha
	'database' => 'banco-de-dados' //Nome do banco a ser conectado
];
```

##### rotas.php

Neste arquivo será configurado as rotas do sistema, você poderá criar grupos de rotas para facilitar o desenvolvimento. 

###### Rotas de erros

Você pode setar rotas para erros 404, 400 e 500

```php
// O método a ser chamado é o 
$Rotas->onErro("CODIGO DO ERRO","CONTROLLER::METODO");


// Exemplo, em caso de erro 404 deve ser chamado  
// o método erro404 da controller Error
$Rotas->onErro("404","Error::erro404");


// Pode ser chamado de modo simples tambem, 
// Execuntando um método dentro desse método. 
// Exemplo: Em caso de erro 404 será imprimido na tela "Erro - 404"
$Rotas->onError("404", function (){
   echo "Erro - 404";
});
```

###### Criando uma rota sem grupo 

```php
// O método a ser chamado é o 
$Rotas->on("TIPO DA REQUISICAO","ROTA","CONTROLLER::METODO");

// Vamos supor que a index do seu sistema deve chamar a 
// controller Principal e método index 
$Rotas->on("GET","","Principal::index");

// O tipo pode ser: GET, POST, DELETE, PUT

// Vamos supor que a rota de inserir um produto e alterar é a mesma 
// o que muda é o tipo e o metodo a ser chamado
$Rotas->on("POST","produto","Produto::insert");
$Rotas->on("PUT","produto","Produto::update");

```


###### Criando um grupo de rotas

Muitas vezes utilizamos varias rotas que possui o mesmo prefixo, Ex: produtos, produtos/insert...
para isso podemos criar um grupo para não ficar escrevendo a mesma coisa sempre.

```php
// O método a ser chamado é o 
$Rotas->group("NOME DO GRUPO","PREFIXO DA ROTA","CONTROLLER");

// Vamos supor que você precisa criar um grupo para controller 
// Produto
$Rotas->group("grupoProduto","produto","Produto");
```

###### Criando uma rota com grupo

```php
// O método a ser chamado é o 
$Rotas->onGroup("NOME DO GRUPO","TIPO","ROTA","METODO");

// Agora você precisa criar as rotas do grupo Produto
$Rotas->onGroup("grupoProduto","GET","listar","listar");
```
