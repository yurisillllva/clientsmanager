Clientes Manager
Este projeto é uma aplicação web desenvolvida para gerenciar clientes capturados a partir de um Flow teste da Huggy, com integração de webhooks e provedor VoIP. A aplicação consiste em um backend desenvolvido em Laravel e um frontend em Vue.js.

Documentação API: https://documenter.getpostman.com/view/19872017/2sAYkHoyEP

Tecnologias Utilizadas
Backend (Laravel)
PHP 7.4

Laravel 8

MySQL 5.7

PHPUNIT

JWT (JSON Web Tokens) para autenticação

Eloquent ORM para consultas ao banco de dados

Webhooks para recebimento de dados externos

Tarefas Agendadas para envio de e-mails de boas-vindas

Integração com Provedor VoIP

Frontend (Vue.js)
Vue.js 3

Vue Router para navegação

Axios para requisições HTTP

Bootstrap 5 para estilização

Chart.js para gráficos

Vuelidate para validação de formulários

Instalação e Execução
Pré-requisitos
PHP 7.4

Composer

Node.js (recomendado versão 14 ou superior)

MySQL 5.7

Git

Passo a Passo
1. Clone o Repositório
bash
Copy
git clone https://github.com/seu-usuario/huggy-challenge.git
cd huggy-challenge
2. Configuração do Backend (Laravel)
Instale as dependências do PHP:

bash
Copy
composer install

Crie o arquivo .env:

bash
Copy
cp .env.example .env

Configure o arquivo .env:

Defina as variáveis de ambiente para o banco de dados:

env
Copy
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manager_client
DB_USERNAME=root
DB_PASSWORD=
Gere a chave do Laravel:

bash
Copy
php artisan key:generate

Execute as migrations:

bash
Copy
php artisan migrate

Gere a chave secreta do JWT:

bash
Copy
php artisan jwt:secret

Crie um usuário inicial executando a seed e sendo assim com um user autenticado

bash
Copy
php artisan db:seed --class=UsersTableSeeder

Para instalar o Twilio

bash
Copy
composer require twilio/sdk

Para rodar um teste unitário:

bash
Copy
php artisan test --filter AuthControllerTest

Ou todos existentes:

bash
Copy
php artisan test --testsuite=Feature

Inicie o servidor Laravel:

bash
Copy
php artisan serve

3. Configuração do Frontend (Vue.js)

Instale as dependências do Node.js:

bash
Copy
cd frontend
npm install

Configure o arquivo .env:

Inicie o servidor de desenvolvimento:

bash
Copy
npm run serve

4. Executando Tarefas Agendadas
Para enviar e-mails de boas-vindas após 30 minutos do cadastro de um cliente, execute o comando:

bash
Copy
php artisan schedule:work

Estrutura do Projeto

Backend

Controllers: Lógica de controle para as rotas da API.

Models: Definição dos modelos de dados.

Repositories: Padrão Repository para abstração de consultas ao banco de dados.

Migrations: Definição da estrutura do banco de dados.

Requests: Validação de dados de entrada.

Mail: Configuração e templates de e-mails.

Frontend

Components: Componentes reutilizáveis.

Views: Páginas da aplicação.

Router: Configuração de rotas.

Store: Gerenciamento de estado (se aplicável).

Services: Serviços para interação com a API.

Rotas da API
POST /api/login: Autenticação do usuário.

GET /api/clients: Lista de clientes.

POST /api/clients: Cadastro de um novo cliente.

PUT /api/clients/{id}: Atualização de um cliente.

DELETE /api/clients/{id}: Exclusão de um cliente.

POST /api/clients/{client}/call: Iniciar chamada VoIP.

GET /api/charts/states: Dados para gráfico por estado.

GET /api/charts/cities: Dados para gráfico por cidade.

