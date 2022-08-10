<p align="center"><a href="https://yampi.com.br" target="_blank"><img src="https://icons.yampi.me/svg/brand-yampi.svg" width="200"></a></p>

# Teste prático para Back-End Developer
***

Bem-vinda, pessoa desenvolvedora.

Este é o teste que nós, aqui da Yampi, usamos para avaliar tecnicamente todas as pessoas que estão participando do nosso processo seletivo para a vaga de desenvolvimento Back-End.

## TL;DR

- Você deverá criar um CRUD através de uma API REST com Laravel;
- Você deverá criar um comando artisan que se comunicará com uma outra API para importar em seu banco de dados;

## Começando

**Faça um fork desse projeto para iniciar o desenvolvimento. PRs não serão aceitos.**

### Configuração do ambiente
***

**Para configuração do ambiente é necessário ter o [Docker](https://docs.docker.com/desktop/) instalado em sua máquina.**

Dentro da pasta do projeto, rode o seguinte comando: `docker-compose up -d`.

Copie o arquivo `.env.example` a renomeie para `.env` dentro da pasta raíz da aplicação.

```bash
cp .env.example .env
```

Após criar o arquivo `.env`, será necessário acessar o container da aplicação para rodar alguns comandos de configuração do Laravel.

Para acessar o container use o comando `docker exec -it yampi_test_app sh`.

Digite os seguintes comandos dentro do container:

```bash
composer install
php artisan key:generate
php artisan migrate
```

Após rodar esses comandos, seu ambiente estará pronto para começar o teste.

Para acessar a aplicação, basta acessar `localhost:8000`

### Funcionalidades a serem implementadas

**Essa aplicação deverá se comportar como uma API REST, onde será consumida por outros sistemas. Nesse teste você deverá se preocupar em constriuir somente a API**. 

##### CRUD produtos

Aqui você deverá desenvolver as principais operações para o gerenciamento de um catálogo de produtos, sendo elas:

- Criação
- Atualização
- Exclusão

O produto deve ter a seguinte estrutura:

Campo       | Tipo      | Obrigatório   | Pode se repetir
----------- | :------:  | :------:      | :------:
id          | int       | true          | false
name        | string    | true          | false        
price       | float     | true          | true
decription  | text      | true          | true
category    | string    | true          | true
image_url   | url       | false         | true

Os endpoints de criação e atualização devem seguir o seguinte formato de payload:

```json
{
    "name": "product name",
    "price": 109.95,
    "description": "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...",
    "category": "test",
    "image": "https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg"
}
```

**Importante:** Tanto os endpoints de criação é atualização, deverão ter uma camada de validação dos campos.

##### Buscas de produtos

Para realizar a manutenção de um catálogo de produtos é necessário que o sistema tenha algumas buscas, sendo elas:

- Busca pelos campos `name` e `category` (trazer resultados que batem com ambos os campos).
- Busca por uma categoria específica.
- Busca de produtos com e sem imagem.
- Buscar um produto pelo seu ID único.

##### Importação de produtos de uma API externa

É necessário que o sistema seja capaz de importar produtos que estão em um outro serviço. Deverá ser criado um comando que buscará produtos nessa API e armazenará os resultados para a sua base de dados. 

Sugestão: `php artisan products:import`

Esse comando deverá ter uma opção de importar um único produto da API externa, que será encontrado através de um ID externo.

Sugestão: `php artisan products:import --id=123`

Utilize a seguinte API para importar os produtos: [https://fakestoreapi.com/docs](https://fakestoreapi.com/docs)

---

Se houver dúvidas, por favor, abra uma issue nesse repositório. Ficaremos felizes em ajudá-lo ou até mesmo melhorar essa documentação.

---

## Como Testar:

Faça o clone do repositório na sua máquina

```bash
git clone git@github.com:guibmolina/job-backend-developer.git
```

### Configuração do ambiente
***

**Para configuração do ambiente é necessário ter o [Docker](https://docs.docker.com/desktop/) instalado em sua máquina.**

Dentro da pasta do projeto, rode o seguinte comando: `docker-compose up -d`.

Copie o arquivo `.env.example` a renomeie para `.env` dentro da pasta raíz da aplicação.

```bash
cp .env.example .env
```

Após criar o arquivo `.env`, será necessário acessar o container da aplicação para rodar alguns comandos de configuração do Laravel.

Para acessar o container use o comando `docker exec -it yampi_test_app sh`.

Digite os seguintes comandos dentro do container:

```bash
composer install
php artisan key:generate
php artisan migrate
```

## Endpoints

###  Busca por todos os produtos

Requisição
```bash
GET  http://localhost:8000/api/v1/products
```
Resposta
```
[
	{
		"id": 22,
		"name": "Mens Casual Premium Slim Fit T-Shirts ",
		"price": 22.3,
		"category": "men's clothing",
		"description": "Slim-fitting style, contrast raglan long sleeve, three-button henley placket, light weight & soft fabric for breathable and comfortable wearing. And Solid stitched shirts with round neck made for durability and a great fit for casual fashion wear and diehard baseball fans. The Henley style round neckline includes a three-button placket.",
		"image": "https:\/\/fakestoreapi.com\/img\/71-3HjGNDUL._AC_SY879._SX._UX._SY._UY_.jpg"
	},
	{
		"id": 23,
		"name": "Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops",
		"price": 109.95,
		"category": "men's clothing",
		"description": "Your perfect pack for everyday use and walks in the forest. Stash your laptop (up to 15 inches) in the padded sleeve, your everyday",
		"image": "https:\/\/fakestoreapi.com\/img\/81fPKd-2AYL._AC_SL1500_.jpg"
	}
]
```
#### É possivel adicionar filtros na busca:
Buscar por um nome de um produto
```bash
GET  http://localhost:8000/api/v1/products?search[name]=geladeira
```

Buscar por um nome de um produto e por uma categoria
```bash
GET  http://localhost:8000/api/v1/products?search[name]=geladeira&search[category]=degelo
```
Por padrão a listagem retorna produtos com imagens e sem imagens, mas podemos também adicionar um filtro para retornar produtos com ou sem elas: `withImage=true` ou `withImage=false`
```bash
GET  http://localhost:8000/api/v1/products?withImage=true
```
###  Busca por um produto específico

Requisição
```bash
GET  http://localhost:8000/api/v1/products/1
```
Resposta
```
{
	"name": "Mens Casual Premium Slim Fit T-Shirts ",
	"price": 22.3,
	"description": "Slim-fitting style, contrast raglan long sleeve, three-button henley placket, light weight & soft fabric for breathable and comfortable wearing. And Solid stitched shirts with round neck made for durability and a great fit for casual fashion wear and diehard baseball fans. The Henley style round neckline includes a three-button placket.",
	"category": "men's clothing",
	"image": "https:\/\/fakestoreapi.com\/img\/71-3HjGNDUL._AC_SY879._SX._UX._SY._UY_.jpg"
}
```

###  Busca produtos por uma categoria específica

Requisição
```bash
GET  http://localhost:8000/api/v1/products/categories/men's clothing
```
Resposta
```
[
	{
		"id": 22,
		"name": "Mens Casual Premium Slim Fit T-Shirts ",
		"price": 22.3,
		"category": "men's clothing",
		"description": "Slim-fitting style, contrast raglan long sleeve, three-button henley placket, light weight & soft fabric for breathable and comfortable wearing. And Solid stitched shirts with round neck made for durability and a great fit for casual fashion wear and diehard baseball fans. The Henley style round neckline includes a three-button placket.",
		"image": "https:\/\/fakestoreapi.com\/img\/71-3HjGNDUL._AC_SY879._SX._UX._SY._UY_.jpg"
	},
	{
		"id": 23,
		"name": "Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops",
		"price": 109.95,
		"category": "men's clothing",
		"description": "Your perfect pack for everyday use and walks in the forest. Stash your laptop (up to 15 inches) in the padded sleeve, your everyday",
		"image": "https:\/\/fakestoreapi.com\/img\/81fPKd-2AYL._AC_SL1500_.jpg"
	}
]
```

###  Criar um produto

Requisição
```
POST  http://localhost:8000/api/v1/products

body:
{
    "name": "product name",
    "price": 109.95,
    "description": "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...",
    "category": "test",
    "image": "https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg"
}
```
Campo       | Tipo      | Obrigatório   
----------- | :------:  | :------:        
name        | string    	 | true                 
price       | float     	 | true      
decription  | string    	 | true         
category    | string    	 | true         
image_url   | string (url)   | false  

Resposta
```
{
	"id": 42
}
```     


###  Atualizar um produto

Requisição
```
PUT  http://localhost:8000/api/v1/products/{id}

body:
{
    "name": "product name test",
    "price": 109.95,
    "description": "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...",
    "category": "test",
    "image": "https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg"
}
```
Campo       | Tipo      | Obrigatório   
----------- | :------:  | :------:        
name        | string    	 | true                 
price       | float     	 | true      
decription  | string    	 | true         
category    | string    	 | true         
image_url   | string (url)   | false   

Resposta
```
{
	"id": 42
}
```

###  Excluir um produto

Requisição
```bash
DELETE  http://localhost:8000/api/v1/products/{id}
```


###  Importação de produtos
Podemos realizar a importação dos produtos que estão na API [https://fakestoreapi.com/products](https://fakestoreapi.com/products)

*OBS: executar os comandos abaixo dentro do container yampi_test_app*

#### Importar todos os produtos 
`php artisan products:import`

#### Importar um produto  específico 
`php artisan products:import --id={id}`

***
### Configuração do ambiente de testes
Para conseguir rodar os testes de integração e os unitários, rodar o seguinte CREATE dentro do container de banco de dados (yampi_test_db);

`CREATE database testing;`

E depois executar dentro do container yampi_test_app

 `php vendor/bin/phpunit tests` 
***

