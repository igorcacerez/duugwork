# framework-php MVC
Framework MVC simples desenvolvido em PHP. facilita o desenvolvimento de pequenas aplicações em MVC


# Estrutura

App (Contem as páginas da aplicação)
Arquivos (Arquivos de imagens, Css, js e plugins js)
Sistema (Arquivos Proprios do FrameWork)
index.php
.htacess 


# Estruta da Pasta APP

APP/Config 

- Arquivo config.php 

Neste arquivo você irá configurar a rota do seu. 


- Arquivo constantes.php 

Neste arquivo você poderá adicionar constantes para ser utilizadas em todo o sistema. 


- Arquivo database.php 

Neste arquivo você poderá configurar as informações do seu banco de dados MYSQL

- Arquivo rotas.php 

Neste arquivo você poderá configurar as rotas do seu sistema da seguinte forma: 

$rotas [
          "PRIMEIRO ITEM DA URL " => [
          "controller" => "CONTROLLER QUE DEVE SER CHAMADO",
          "metodos" => [
               "SEGUNDO ITEM DA URL" => [
                  "metodo" => "METODO DO CONTROLLER QUE DEVE SER CHAMADO",
                   "parametros" => "QUANTIDADE ITENS QUE DEVE PASSAR POR PARAMETROS"
             ],
          ],
       ]
 ];
 
 
 
