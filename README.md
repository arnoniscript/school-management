# Sistema de Gestão de Matrículas

Trata-se de uma integração de tabelas (students, courses e enrollments) iterada por roles específicas de usuários (admin ou student) para criação, edição e exclusão de cursos, estudantes e matrículas.

## Funcionalidades para usuários Student

-   Ver lista de cursos
-   Filtrar cursos
-   Matricular-se em um curso (limitado a 1 vez por curso disponível)

## Funcionalidades para usuários Admin

-   Ver, criar, editar ou deletar (em massa ou unitariamente) cursos
-   Ver, criar, editar ou deletar (em massa ou unitariamente) estudantes
-   Ver, criar, editar ou deletar (em massa - através do menu Gestão de Matrículas, ou unitariamente - através do item matrícula específico de cada curso ou do gestão de matrículas) matrículas

## Regras de Negócio

-   Ao criar um curso, o admin seleciona a data máxima de matrícula e o número de vagas. Students podem se matricular caso: A data não for ultrapassada e o número de vagas for maior que 0. Admins podem gerir as matrículas livremente, independente das condições.
-   Usuários podem se registrar livremente, entretanto, somente conseguirão fazer matrícula caso um estudante esteja cadastrado com o mesmo Email do usuário.

[![Laravel](https://img.shields.io/badge/Laravel-11.33.2-green.svg)](https://choosealicense.com/licenses/mit/)
[![Php](https://img.shields.io/badge/PHP-8.3.14-green.svg)](https://opensource.org/licenses/)

## Rodando localmente

Após clonar o projeto e acessar a pasta correspondente, configure o .env com base no .env.example.

Instale as dependências iniciais

```bash
  composer install
```

Suba o Docker

```bash
  ./vendor/bin/sail up
```

Gere a chave da aplicação

```bash
  ./vendor/bin/sail artisan key:generate
```

Execute as migrações e os seeders

```bash
  ./vendor/bin/sail artisan migrate --seed
```

Instale as dependências do front-end com NPM

```bash
vendor/bin/sail npm install
```

Execute o Vite

```bash
vendor/bin/sail npm run dev

```

Pronto, seu sistema está rodando.

## Importante

Para facilitar, se o Seeder foi rodado corretamente você já poderá acessar o sistema com os seguintes dados de autenticação:

### Student:

Login: student@example.com ||
Senha: senha123

### Admin:

Login: admin@example.com || Senha:
senha123

## Rodando os testes

Para rodar os testes, é recomendado utilizar um .env.testing (espelhado no env.example). Caso não utilize, modifique os parâmetros do phpunit.xml

Para rodar, utilize o comando

```bash
  vendor/bin/sail test
```

## Screenshots

![Captura de Tela 2024-11-26 às 17 01 48](https://github.com/user-attachments/assets/20c7ed93-49ae-412f-90cd-8d5cab5bc900)
![Captura de Tela 2024-11-26 às 17 01 55](https://github.com/user-attachments/assets/2699a879-cd23-43b3-8bdc-c5f6ce71a4fc)
![Captura de Tela 2024-11-26 às 17 02 04](https://github.com/user-attachments/assets/a6028ccd-700d-4977-9fc7-d89ea98a991e)
![Captura de Tela 2024-11-26 às 17 02 15](https://github.com/user-attachments/assets/9f7a57c7-2bb9-4a80-8693-10a33b9b26a9)
![Captura de Tela 2024-11-26 às 17 02 30](https://github.com/user-attachments/assets/9fb1c1be-0577-41ed-b57b-97b24eced074)
![Captura de Tela 2024-11-26 às 17 02 43](https://github.com/user-attachments/assets/da20d2d0-a357-4650-bd00-8a723feb1114)
![Captura de Tela 2024-11-26 às 17 02 54](https://github.com/user-attachments/assets/ab0826de-516a-4d77-9d7b-86c73bf64c01)

