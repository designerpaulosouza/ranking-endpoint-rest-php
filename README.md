# `Ranking de Movimentos - API e Frontend (PHP 7 + Bootstrap)`

Este projeto consiste em uma API REST desenvolvida em PHP 7 que fornece um ranking de recordes pessoais para diferentes movimentos de treino, e uma interface web que consome essa API e exibe os dados utilizando Bootstrap.

---

## `1. Como Configurar e Utilizar`

### `1.1 Requisitos`

- PHP 7
- Servidor Apache ou Nginx
- MySQL
- Extensão cURL do PHP habilitada

### `1.2 Configuração do Banco de Dados`

Execute os comandos abaixo no MySQL para criar e popular o banco de dados:

```sql
CREATE DATABASE ranking_db;
USE ranking_db;

CREATE TABLE `user` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `movement` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `personal_record` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `movement_id` int NOT NULL,
    `value` FLOAT NOT NULL,
    `date` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `personal_record` ADD CONSTRAINT `personal_record_fk0` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`);
ALTER TABLE `personal_record` ADD CONSTRAINT `personal_record_fk1` FOREIGN KEY (`movement_id`) REFERENCES `movement`(`id`);

INSERT INTO `user` (id, name) VALUES
(1, 'Joao'),
(2, 'Jose'),
(3, 'Paulo');

INSERT INTO movement (id, name) VALUES
(1, 'Deadlift'),
(2, 'Back Squat'),
(3, 'Bench Press');

INSERT INTO personal_record (id, user_id, movement_id, value, `date`) VALUES
(1, 1, 1, 100.0, '2021-01-01 00:00:00'),
(2, 1, 1, 180.0, '2021-01-02 00:00:00'),
(3, 1, 1, 150.0, '2021-01-03 00:00:00'),
(4, 2, 1, 190.0, '2021-01-06 00:00:00'),
(5, 3, 1, 170.0, '2021-01-01 00:00:00'),
(6, 1, 2, 130.0, '2021-01-03 00:00:00'),
(7, 2, 2, 130.0, '2021-01-03 00:00:00'),
(8, 3, 2, 125.0, '2021-01-03 00:00:00');
```

### `1.3 Configuração do Projeto`

1. Clone o repositório:

```sh
git clone https://github.com/designerpaulosouza/ranking-endpoint-rest-php.git
cd ranking-endpoint-rest-php
```

2. Configure as credenciais do banco no arquivo `api.php`:

```php
$pdo = new PDO("mysql:host=localhost;dbname=ranking_db;charset=utf8", "usuario", "senha");
```

3. Inicie o servidor PHP (caso não tenha Apache configurado):

```sh
php -S localhost:8080
```

4. Acesse `http://localhost:8080/?movement_id=2` no navegador, informe números entre 1 e 3, ou utilize o select.

---

## `2. Estrutura do Projeto`

```
php-ranking-api/
│-- api.php       # API REST que retorna o ranking de um movimento
│-- index.php     # Frontend que consome a API e exibe os dados
│-- README.md     # Documentação do projeto
```

### `2.1 API (api.php)`

- Recebe um `movement_id` via POST "usando o Postman", e GET via url ou utilizando o select, e retorna um ranking dos usuários ordenado pelo maior recorde pessoal.
- Exemplo de chamada via cURL:

```sh
curl -X POST http://localhost:8000/ranking-endpoint-rest-php/api.php -H "Content-Type: application/json" -d '{"movement_id":1}'
```

### `2.2 Frontend (index.php)`

- Envia uma requisição para a API via `cURL` e exibe os dados formatados em uma tabela Bootstrap.
- Pode ser acessado diretamente no navegador.

---

## `3. Subindo para o GitHub`

Caso precise subir o projeto para o GitHub, siga os passos abaixo:

```sh
git init
git add .
git commit -m "Primeira versão do projeto"
git branch -M main
git remote add origin https://github.com/seu-usuario/ranking-endpoint-rest-php.git
git push -u origin main
```
