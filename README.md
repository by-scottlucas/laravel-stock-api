# **Laravel - Stock API**

## **Introdução**

Projeto de uma API RESTful desenvolvida em **PHP** com o framework **Laravel**, voltada para a gestão de estoque.
O projeto conta com autenticação além de **controllers** dedicados para o gerenciamento de **produtos**, **categorias** e **movimentações de estoque**.
Cada endpoint implementa **HATEOAS**, garantindo maior navegabilidade e usabilidade dos recursos disponíveis na API.

---

## **Estrutura do Projeto**

```bash
├── app/
│   ├── Enums/                         # Definições de enums usados na aplicação (ex: tipos de movimentação de estoque)
│   ├── Exceptions/                    # Classes de exceção personalizadas
│   ├── Http/
│   │   ├── Controllers/               # Controllers da aplicação (Auth, Product, Category, StockMovement)
│   │   └── Resources/                 # Transformadores e formatação de respostas da API (API Resources)
│   ├── Models/                        # Modelos Eloquent para manipulação e mapeamento do banco de dados
│   └── Providers/                     # Service Providers para registrar e configurar serviços da aplicação
├── config/                            # Arquivos de configuração do Laravel e pacotes
├── database/
│   ├── factories/                     # Classes para gerar dados falsos (faker) em testes
│   ├── migrations/                    # Estrutura das tabelas e alterações do banco de dados
│   └── seeders/                       # População inicial do banco (dados padrão/exemplo)
├── routes/
│   └── api.php                        # Definição das rotas da API RESTful
├── tests/                             # Testes automatizados (unitários e funcionais)
├── .env.example                       # Exemplo de variáveis de ambiente para configuração local
├── .gitignore                         # Arquivos e pastas ignorados pelo Git
├── artisan                            # CLI do Laravel para executar comandos
├── composer.json                      # Definição de dependências PHP e scripts do Composer
├── LICENSE                            # Arquivo de licença do projeto
└── README.md                          # Documentação principal do projeto
```

---

## **Como rodar o projeto**

1. **Clone o repositório**

```bash
git clone https://github.com/by-scottlucas/laravel-stock-api.git
cd laravel-stock-api
```

2. **Instale as dependências**

```bash
composer install
```

3. **Configure variáveis de ambiente**

Crie um arquivo `.env` na raiz do projeto e configure as variáveis necessárias (exemplo):

```env
APP_NAME=LaravelStockAPI
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stock_api
DB_USERNAME=root
DB_PASSWORD=
```

4. **Execute as migrations**

```bash
php artisan migrate
```

5. **Inicie o servidor de desenvolvimento**

```bash
php artisan serve
```

6. Acesse no navegador ou via ferramenta de API (Postman/Insomnia):

```
http://localhost:8000/api/v1
```

---

## **Endpoints da API**

### 🔑 Autenticação

* `POST /auth/register` — Registrar novo usuário
* `POST /auth/login` — Login e geração de token
* `GET /auth/me` — Retorna dados do usuário autenticado (**protegida**)
* `POST /auth/logout` — Logout e invalidação do token (**protegida**)

---

### 📦 Produtos

* `GET /products` — Listar todos os produtos (**protegida**)
* `POST /products` — Criar um novo produto (**protegida**)
* `GET /products/{id}` — Exibir um produto específico (**protegida**)
* `PUT /products/{id}` — Atualizar dados de um produto (**protegida**)
* `DELETE /products/{id}` — Remover um produto (**protegida**)

---

### 🗂️ Categorias

* `GET /categories` — Listar categorias (**protegida**)
* `POST /categories` — Criar categoria (**protegida**)
* `GET /categories/{id}` — Exibir categoria (**protegida**)
* `PUT /categories/{id}` — Atualizar categoria (**protegida**)
* `DELETE /categories/{id}` — Remover categoria (**protegida**)

---

### 📊 Movimentações de Estoque

* `GET /products/stock/movements` — Listar movimentações (**protegida**)
* `GET /products/stock/movements/{id}` — Detalhar movimentação (**protegida**)
* `DELETE /products/stock/movements/{id}` — Remover movimentação (**protegida**)
* `POST /products/{id}/stock/in` — Entrada de estoque (**protegida**)
* `POST /products/{id}/stock/out` — Saída de estoque (**protegida**)

---

## **Tecnologias Utilizadas**

* [Laravel 12](https://laravel.com/) — Framework PHP moderno e robusto.
* [Laravel Sanctum](https://laravel.com/docs/sanctum) — Autenticação baseada em tokens para APIs.
* [MySQL](https://www.mysql.com/) — Banco de dados relacional.
* [PHPUnit](https://phpunit.de/) — Framework de testes para PHP.
* [Composer](https://getcomposer.org/) — Gerenciador de dependências PHP.

---

## **Licença**

Este projeto está licenciado sob a [Licença MIT](./LICENSE)

## **Autor**

Este projeto foi desenvolvido por **Lucas Santos Silva**, Desenvolvedor Full Stack, graduado pela **Escola Técnica do Estado de São Paulo (ETEC)** nos cursos de **Informática (Suporte)** e **Informática para Internet**.

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/bylucasss/)
