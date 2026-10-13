# 🛠️ Guia de Desenvolvimento — Minty Backend

Bem-vindo ao guia técnico e de desenvolvimento do **Minty Backend**. Este documento foi criado para fornecer a desenvolvedores uma visão profunda sobre a arquitetura da aplicação, padrões de projeto, ciclo de vida das requisições, configuração do ambiente e, em especial, o gerenciamento de migrações de banco de dados com Doctrine.

---

## 📋 Sumário

1. [Visão Geral & Stack Tecnológica](#-visão-geral--stack-tecnológica)
2. [Ambiente & Pré-requisitos](#-ambiente--pré-requisitos)
3. [Passo a Passo de Inicialização (Setup Inicial)](#-passo-a-passo-de-inicialização-setup-inicial)
4. [Guia Completo de Migrations (Doctrine)](#-guia-completo-de-migrations-doctrine)
   - [Conceito e Estrutura](#conceito-e-estrutura)
   - [Comandos Essenciais](#comandos-essenciais)
   - [Fluxo de Trabalho: Criando e Aplicando Alterações](#fluxo-de-trabalho-criando-e-aplicando-alterações)
   - [Rollback e Resolução de Conflitos](#rollback-e-resolução-de-conflitos)
5. [Arquitetura Técnica da Aplicação](#-arquitetura-técnica-da-aplicação)
   - [Clean Architecture & Domain-Driven Design (DDD)](#clean-architecture--domain-driven-design-ddd)
   - [Camada de Domínio (`src/Domain`)](#1-camada-de-domínio-srcdomain)
   - [Camada de Aplicação (`src/App`)](#2-camada-de-aplicação-srcapp)
   - [Camada de Infraestrutura (`src/Infra`)](#3-camada-de-infraestrutura-srcinfra)
6. [Mecanismos Transversais](#-mecanismos-transversais)
   - [Autenticação e Autorização (JWT & Sessions)](#autenticação-e-autorização-jwt--sessions)
   - [Validação de Entrada (`RequestValidator` & Schemas)](#validação-de-entrada-requestvalidator--schemas)
   - [Tratamento Global de Erros (`ExceptionListener`)](#tratamento-global-de-erros-exceptionlistener)
   - [Padronização de Respostas HTTP (`ResponseFactory`)](#padronização-de-respostas-http-responsefactory)
7. [Injeção de Dependências e Estratégia de Testes](#-injeção-de-dependências-e-estratégia-de-testes)
   - [Configuração de Ambientes (`services.yml`)](#configuração-de-ambientes-servicesyml)
   - [Testes com PHPUnit & Fake Repositories](#testes-com-phpunit--fake-repositories)
8. [Documentação de API (Swagger / OpenAPI)](#-documentação-de-api-swagger--openapi)
9. [Guia Passo a Passo: Criando uma Nova Funcionalidade](#-guia-passo-a-passo-criando-uma-nova-funcionalidade)
10. [Troubleshooting & Perguntas Frequentes](#-troubleshooting--perguntas-frequentes)

---

## 🚀 Visão Geral & Stack Tecnológica

O **Minty** é uma plataforma financeira pessoal que suporta controle de receitas/despesas, gestão de contas e transferências ponto a ponto (P2P). O backend foi projetado com forte foco em **Clean Architecture**, **Domain-Driven Design (DDD)** e segurança.

### Tecnologias Utilizadas:
* **PHP 8.4+** (utilizando recursos modernos como *Property Hooks*, visibilidade assimétrica `public private(set)` e atributos).
* **Symfony 8.0 (MicroKernel)**:
  * `symfony/framework-bundle`
  * `symfony/dependency-injection`
  * `symfony/routing`
  * `symfony/validator`
  * `symfony/http-kernel` & `symfony/http-foundation`
* **Doctrine ORM 3.x & Doctrine Migrations 3.x**: Persistência desacoplada e versionamento de esquema.
* **PostgreSQL 16**: Banco de dados relacional.
* **Firebase PHP-JWT**: Geração e validação de tokens JWT.
* **NelmioApiDocBundle 5.x**: Documentação interativa Swagger/OpenAPI.
* **PHPUnit 12.x**: Suíte de testes unitários e de integração com dublês em memória (*Fakes*).

---

## 💻 Ambiente & Pré-requisitos

Antes de iniciar, certifique-se de possuir instalado em sua máquina:
* **PHP 8.4** com as seguintes extensões ativas:
  * `pdo_pgsql`, `pgsql`
  * `sodium`
  * `mbstring`
  * `openssl`
  * `intl`
  * `ctype`
  * `json`
* **Composer 2.x**
* **Docker & Docker Compose** (para executar o PostgreSQL localmente)

---

## 🏁 Passo a Passo de Inicialização (Setup Inicial)

### 1. Clonar e Acessar o Diretório
```bash
cd backend
```

### 2. Instalar Dependências
```bash
composer install
```

### 3. Configurar as Variáveis de Ambiente
Copie o arquivo de exemplo para `.env`:
```bash
cp .env.example .env
```

Edite o arquivo `.env` preenchendo as chaves necessárias:
```dotenv
APP_SECRET=sua_chave_secreta_symfony_aqui
JWT_SECRET=sua_chave_secreta_jwt_super_segura
DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=16&charset=utf8"
APP_ENV=dev
APP_DEBUG=true
```

| Variável | Descrição |
| :--- | :--- |
| `APP_SECRET` | Chave de segurança interna do kernel do Symfony para assinatura de cookies e tokens de sessão. |
| `JWT_SECRET` | Segredo utilizado para gerar e verificar a assinatura HS256 dos tokens JWT de autenticação. |
| `DATABASE_URL` | DSN de conexão ao PostgreSQL, compatível com Doctrine DBAL. |
| `APP_ENV` | Define o ambiente (`dev`, `test`, `prod`). |
| `APP_DEBUG` | Ativa (`true`) ou desativa (`false`) o modo de depuração e logs verbosos. |

### 4. Subir o Banco de Dados com Docker
O projeto conta com uma configuração pronta de PostgreSQL 16 no `docker-compose.yml`:
```bash
docker compose up -d
```

> [!TIP]
> Se seu usuário não estiver no grupo `docker`, você pode precisar executar com `sudo docker compose up -d` ou adicionar seu usuário ao grupo via `sudo usermod -aG docker $USER`.

Para verificar se o container está saudável:
```bash
docker compose ps
```

### 5. Executar as Migrations
Com o banco ativo e o `.env` configurado, execute:
```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

### 6. Iniciar o Servidor de Desenvolvimento
Você pode rodar com o servidor embutido do PHP:
```bash
php -S 0.0.0.0:8000 index.php
```
Ou, caso possua o binário do Symfony CLI:
```bash
symfony server:start
```

A API estará acessível em `http://localhost:8000` e a documentação interativa em `http://localhost:8000/api/doc`.

---

## 🗄️ Guia Completo de Migrations (Doctrine)

O controle do banco de dados relacional é gerenciado pelo **Doctrine Migrations Bundle**.

### Conceito e Estrutura
* O arquivo de configuração está em [`src/Infra/Http/Config/packages/doctrine_migrations.yaml`](src/Infra/Http/Config/packages/doctrine_migrations.yaml).
* As classes de migração ficam em [`migrations/`](migrations/) sob o namespace `DoctrineMigrations`.
* O Doctrine mantém uma tabela chamada `doctrine_migration_versions` no banco de dados para rastrear exatamente quais migrações já foram executadas.
* O console da aplicação está disponível em [`bin/console`](bin/console).

### Comandos Essenciais

#### 1. Criar o Banco de Dados
Caso o banco especificado na `DATABASE_URL` ainda não tenha sido criado pelo Postgres:
```bash
php bin/console doctrine:database:create --if-not-exists
```

#### 2. Verificar o Status das Migrações
Exibe quantas migrações existem, quais já foram aplicadas e se há versões pendentes:
```bash
php bin/console doctrine:migrations:status
```
Para ver uma lista detalhada de todas as versões disponíveis e o estado de cada uma:
```bash
php bin/console doctrine:migrations:list
```

#### 3. Executar Migrações Pendentes (Up)
Aplica todas as migrações que ainda não foram executadas:
```bash
php bin/console doctrine:migrations:migrate
```
Em pipelines CI/CD ou scripts de inicialização, utilize a flag `--no-interaction` para evitar prompts interativos:
```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

#### 4. Gerar Nova Migração via Diff (Automático)
Compara os metadados das entidades Doctrine em [`src/Infra/Db/Entity/`](src/Infra/Db/Entity/) com o estado atual do banco PostgreSQL e gera um arquivo com o SQL correspondente:
```bash
php bin/console doctrine:migrations:diff
```

#### 5. Gerar uma Migração em Branco (Manual)
Caso queira escrever SQL ou comandos DDL manualmente (por exemplo, views, índices customizados ou triggers):
```bash
php bin/console doctrine:migrations:generate
```

#### 6. Validar o Schema do Banco
Verifica se as anotações/atributos das entidades Doctrine são válidos e se o banco está 100% sincronizado com as entidades:
```bash
php bin/console doctrine:schema:validate
```

#### 7. Pré-visualizar o SQL Sem Executar
Para inspecionar as queries DDL que o Doctrine executaria:
```bash
php bin/console doctrine:schema:update --dump-sql
```

---

### Fluxo de Trabalho: Criando e Aplicando Alterações

Quando você precisar adicionar uma nova tabela, coluna ou índice:

```mermaid
flowchart TD
    A["1. Modificar Entity em src/Infra/Db/Entity"] --> B["2. php bin/console doctrine:migrations:diff"]
    B --> C["3. Revisar arquivo em migrations/VersionYYYYMMDD.php"]
    C --> D["4. php bin/console doctrine:migrations:migrate"]
    D --> E["5. php bin/console doctrine:schema:validate"]
    E --> F["6. Atualizar Mappers e Entidades de Domínio"]
```

1. **Atualize ou crie a entidade Doctrine**:
   Edite uma classe existente em `src/Infra/Db/Entity/` ou crie uma nova anotada com os atributos `#[ORM\Entity]`, `#[ORM\Table]`, `#[ORM\Column]`.
2. **Gere o diff de migração**:
   ```bash
   php bin/console doctrine:migrations:diff
   ```
3. **Inspecione o arquivo gerado**:
   Abra o arquivo recém-gerado em `migrations/Version<TIMESTAMP>.php`. Verifique cuidadosamente os métodos `up(Schema $schema)` e `down(Schema $schema)`. Se necessário, ajuste tipos de dados ou renomeações.
4. **Aplique a migração**:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```
5. **Valide a sincronização**:
   ```bash
   php bin/console doctrine:schema:validate
   ```
6. **Mantenha os Mappers e Domínio alinhados**:
   Atualize o Mapper respectivo em `src/Infra/Db/Mapper/` e, se necessário, as entidades de negócio em `src/Domain/Entities/`.

---

### Rollback e Resolução de Conflitos

#### Reverter a última migração aplicada:
```bash
php bin/console doctrine:migrations:migrate prev
```

#### Reverter todas as migrações (voltar ao estado zero):
```bash
php bin/console doctrine:migrations:migrate first
```

#### Executar rollback de uma versão específica:
```bash
php bin/console doctrine:migrations:execute DoctrineMigrations\Version<TIMESTAMP> --down
```

#### Corrigir estado da tabela de metadados:
Se você alterou arquivos de migração já executados ou adicionou versões manualmente:
```bash
php bin/console doctrine:migrations:sync-metadata-storage
```

---

## 🏛️ Arquitetura Técnica da Aplicação

### Clean Architecture & Domain-Driven Design (DDD)

A aplicação segue rigorosamente os princípios da **Clean Architecture** (Arquitetura Limpa) e **DDD**. A regra fundamental de dependência estabelece que o código das camadas mais internas nunca conhece as camadas mais externas:

```
┌─────────────────────────────────────────────────────────┐
│               Infraestrutura (Framework & IO)           │
│   Controllers, Doctrine Entities, Repositórios SQL,    │
│   Event Listeners, Configurações do Symfony, JWT         │
│   ┌─────────────────────────────────────────────────┐   │
│   │             Aplicação (Casos de Uso)            │   │
│   │   Usecases, DTOs, Serviços de Domínio/Token     │   │
│   │   ┌─────────────────────────────────────────┐   │   │
│   │   │          Domínio (Regras Puras)         │   │   │
│   │   │   Entidades de Negócio, Value Objects,  │   │   │
│   │   │   Exceções de Domínio, Interfaces Repo  │   │   │
│   │   └─────────────────────────────────────────┘   │   │
│   └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
```

---

### 1. Camada de Domínio (`src/Domain`)
A camada de domínio é isolada e não possui nenhuma dependência do Symfony, Doctrine ou bibliotecas externas. Ela contém:

* **Entidades de Negócio (`Domain/Entities`)**:
  * [`User`](src/Domain/Entities/User.php): Gerencia dados do usuário, comparação e alteração segura de senhas com hashing, validação de regras de nome e e-mail.
  * [`Account`](src/Domain/Entities/Account.php): Gerencia saldo financeiro, status de ativação, operações de depósito (`deposit`), saque (`withdraw`) e transferência atômica com compensação imediata (`transfer`).
  * [`Category`](src/Domain/Entities/Category.php): Categorização de transações financeiras.
  * [`Transaction`](src/Domain/Entities/Transaction.php): Representa um fluxo financeiro (`TransactionEnum::INFLOW` ou `TransactionEnum::OUTFLOW`) com status (`PENDING`, `DONE`, `CANCELLED`).
  * [`Session`](src/Domain/Entities/Session.php): Representa a sessão ativa do usuário com hash de refresh token (`tokenHash`), validade de 7 dias, rotação atômica (`rotate`) e proteção contra timing-attacks com `hash_equals`.

> [!NOTE]
> **Padrão Factory (`create` vs `restore`)**:
> As entidades de domínio possuem construtores privados e são instanciadas via métodos estáticos:
> * `create(...)`: Usado para **novas** entidades de negócio (gera novos UUIDs, define datas correntes e estados iniciais como `isActive = true`).
> * `restore(...)`: Usado pelos Mappers para **reconstituir** uma entidade existente do banco de dados mantendo os dados preservados.

* **Value Objects (`Domain/ValueObject`)**:
  * [`Money`](src/Domain/ValueObject/Money.php): Garante precisão financeira armazenando o valor em **centavos** (`int`). Evita imprecisões clássicas de ponto flutuante. Métodos: `add()`, `subtract()`, `greaterOrEqual()`.
  * [`Password`](src/Domain/ValueObject/Password.php): Validação estrita de complexidade (mínimo de 8 caracteres, letra maiúscula, minúscula, número e caractere especial). Armazena o hash Bcrypt.
  * [`Email`](src/Domain/ValueObject/Email.php): Validação de conformidade RFC de e-mails.
  * [`UUID`](src/Domain/ValueObject/UUID.php): Identificador único universal imutável v4.

* **Interfaces de Repositório (`Domain/Repository`)**:
  * Declaram os contratos que o domínio e os casos de uso exigem:
    * `UserRepository`, `AccountRepository`, `CategoryRepository`, `TransactionRepository`, `SessionRepository`.

* **Erros de Domínio (`Domain/Error`)**:
  * Exceções puras de negócio: `InsufficientFunds`, `ConflictPassword`, `CategoryInactive`, `InvalidAmount`, `WeakPassword`, etc.

---

### 2. Camada de Aplicação (`src/App`)
A camada de aplicação orquestra a lógica de negócio necessária para executar uma ação:

* **Casos de Uso (`App/Usecases`)**:
  * Cada caso de uso é uma classe focada em um único fluxo, contendo o método `execute()`:
    * **Finanças**: [`DepositUsecase`](src/App/Usecases/DepositUsecase.php), [`WithdrawUsecase`](src/App/Usecases/WithdrawUsecase.php), [`TransferUsecase`](src/App/Usecases/TransferUsecase.php), [`ListTransactionsUsecase`](src/App/Usecases/ListTransactionsUsecase.php).
    * **Contas & Categorias**: `CreateAccountUsecase`, `UpdateAccountUsecase`, `DeactiveAccountUsecase`, `CreateCategoryUsecase`, `UpdateCategoryUsecase`, `DeactiveCategoryUsecase`.
    * **Autenticação & Usuários**: `LoginUsecase`, `LogoutUsecase`, `RefreshTokenUsecase`, `CreateUserUsecase`, `ChangePasswordUsecase`, `ChangeEmailUsecase`.
* **DTOs (`App/DTO`)**:
  * Objetos simples e fortemente tipados para transferir parâmetros de entrada e resultados de saída dos Use Cases.
* **Segurança (`App/Security`)**:
  * [`TokenService`](src/App/Security/TokenService.php): Contrato de geração e validação de tokens JWT.
  * [`TokenPayload`](src/App/Security/TokenPayload.php): Estrutura de dados com `userId`, `exp`, etc.

---

### 3. Camada de Infraestrutura (`src/Infra`)
Responsável por conectar a aplicação ao mundo externo (Banco de Dados, HTTP, Framework):

#### A. Banco de Dados (`Infra/Db`)
* **Entidades Doctrine (`Infra/Db/Entity`)**:
  * Mapeadas diretamente para as tabelas PostgreSQL (`Users`, `Accounts`, `Categories`, `Transactions`, `Session`).
* **Mappers (`Infra/Db/Mapper`)**:
  * Implementam o padrão **Data Mapper**. Convertem `Domain Entity <-> Doctrine Entity`:
    * `UserMapper::toDomain(...)` e `UserMapper::toPersistence(...)`
    * `AccountMapper`, `CategoryMapper`, `TransactionMapper`, `SessionMapper`
* **Implementações de Repositório (`Infra/Db/Repository`)**:
  * `DoctrineUserRepository`, `DoctrineAccountRepository`, etc.
  * Utilizam o `EntityManagerInterface` do Doctrine para persistir dados, convertendo entidades de banco para entidades de domínio antes de retorná-las para os Casos de Uso.

#### B. Camada HTTP & Symfony Framework (`Infra/Http`)
* **Kernel (`AppKernel.php`)**:
  * Configuração do MicroKernel que registra bundles, carrega o container de injeção de dependências (`services.yml`) e descobre rotas por atributos.
* **Controladores (`Infra/Http/Controller`)**:
  * [`AuthController`](src/Infra/Http/Controller/AuthController.php): `/login`, `/logout`, `/refresh`
  * [`UserController`](src/Infra/Http/Controller/UserController.php): `/users`, `/users/me`, `/users/search`, `/users/password`, etc.
  * [`AccountController`](src/Infra/Http/Controller/AccountController.php): `/accounts`, `/accounts/{id}/deposit`, `/accounts/{id}/transfer`, etc.
  * [`CategoryController`](src/Infra/Http/Controller/CategoryController.php): `/categories`, etc.

---

## 🔒 Mecanismos Transversais

### Autenticação e Autorização (JWT & Sessions)

A autenticação é dividida em dois tokens:
1. **Access Token (JWT)**: Vida curta, enviado pelo cliente no header:
   ```http
   Authorization: Bearer <access_token>
   ```
2. **Refresh Token**: Enviado via cookie `HttpOnly` (`refresh_token`) com validade de 7 dias, persistido de forma protegida (hash SHA-256) na tabela `Session`.

#### O Atributo `#[RequiresAuth]` e o `AuthListener`
Para proteger qualquer endpoint, basta adicionar o atributo `#[RequiresAuth]` no método do Controller:

```php
#[Route('/accounts', methods: ['POST'])]
#[RequiresAuth]
public function create(Request $request, CreateAccountUsecase $usecase): JsonResponse
{
    // O ID do usuário autenticado é injetado automaticamente nos attributes da Request
    $userId = $request->attributes->get('user_id');
    ...
}
```

O [`AuthListener`](src/Infra/Http/EventListener/AuthListener.php) é executado no evento `kernel.controller`. Ele:
1. Detecta a presença do atributo `#[RequiresAuth]`.
2. Lê o cabeçalho `Authorization: Bearer ...`.
3. Valida a assinatura do token com o `TokenService`.
4. Em caso de sucesso, injeta `$request->attributes->set('user_id', $payload->userId)`.
5. Se ausente ou inválido, lança `UnauthorizedHttpException(401)`.

---

### Validação de Entrada (`RequestValidator` & Schemas)

Ao receber requisições HTTP, o payload JSON é validado antes de chegar aos Casos de Uso pelo [`RequestValidator`](src/Infra/Http/Util/RequestValidator.php) usando os **Schemas** em `src/Infra/Http/Schema/`:

```php
$dto = $requestValidator->validate(
    $request,
    CreateAccountSchema::class,
    CreateAccountDto::class,
    ['userId' => $request->attributes->get('user_id')]
);
```

Os Schemas utilizam os atributos nativos do Symfony Validator:
* `#[Assert\NotBlank]`
* `#[Assert\Email]`
* `#[Assert\Positive]`
* `#[Assert\Length(min: 8)]`

Se a validação falhar, uma `ValidatorException` é disparada automaticamente.

---

### Tratamento Global de Erros (`ExceptionListener`)

A aplicação conta com um filtro centralizado de erros em [`src/Infra/Http/EventListener/ExceptionListener.php`](src/Infra/Http/EventListener/ExceptionListener.php). Qualquer exceção lançada na aplicação ou no domínio é interceptada e convertida em uma resposta JSON padronizada:

```json
{
  "error": true,
  "code": "INSUFFICIENT_FUNDS",
  "message": "Insufficient funds to perform transaction"
}
```

#### Tabela de Mapeamento de Status Codes:
| Exceção | Status HTTP | Código Semântico (`code`) |
| :--- | :---: | :--- |
| `ValidatorException`, `InvalidJsonBody` | **400** | `VALIDATION_ERROR`, `INVALID_JSON_BODY` |
| `InvalidAmount`, `InvalidEmail`, `InvalidPassword` | **400** | `INVALID_AMOUNT`, `INVALID_EMAIL`, `INVALID_PASSWORD` |
| `WrongPassword` | **401** | `WRONG_PASSWORD` |
| `PasswordDoesNotMatch` | **403** | `PASSWORD_DOES_NOT_MATCH` |
| `UserNotFound`, `AccountNotFound`, `CategoryNotFound` | **404** | `USER_NOT_FOUND`, `ACCOUNT_NOT_FOUND`, `CATEGORY_NOT_FOUND` |
| `EmailAlreadyInUse`, `ConflictPassword` | **409** | `EMAIL_ALREADY_IN_USE`, `CONFLICT_PASSWORD` |
| `AccountAlreadyDeactivated`, `CategoryInactive` | **409** | `ACCOUNT_ALREADY_DEACTIVATED`, `CATEGORY_INACTIVE` |
| `InsufficientFunds` | **422** | `INSUFFICIENT_FUNDS` |
| `WeakPassword`, `UnformattedPassword` | **422** | `WEAK_PASSWORD`, `UNFORMATTED_PASSWORD` |
| `Throwable` genérico | **500** | `INTERNAL_SERVER_ERROR` |

---

### Padronização de Respostas HTTP (`ResponseFactory`)

O [`ResponseFactory`](src/Infra/Http/Response/ResponseFactory.php) padroniza o retorno dos controllers:
* `ResponseFactory::success($data, 'Mensagem')` ➔ **200 OK**
* `ResponseFactory::created($data, 'Mensagem')` ➔ **201 Created**
* `ResponseFactory::noContent()` ➔ **204 No Content**

Estrutura JSON:
```json
{
  "success": true,
  "data": { ... },
  "message": "Operação realizada com sucesso"
}
```

---

## 🧪 Injeção de Dependências e Estratégia de Testes

### Configuração de Ambientes (`services.yml`)

O arquivo [`src/Infra/Http/Config/services.yml`](src/Infra/Http/Config/services.yml) configura o container do Symfony com `autowire: true` e `autoconfigure: true`.

#### O Segredo dos Testes Ultrarrápidos: `when@test`
Em ambiente de produção e desenvolvimento, as interfaces de repositório apontam para as classes Doctrine. Porém, sob o ambiente de testes (`APP_ENV=test`), o Symfony substitui as dependências por **Fakes em Memória**:

```yaml
when@test:
    services:
        Src\Domain\Repository\UserRepository:
            class: Tests\fake\FakeUserRepository
        Src\Domain\Repository\AccountRepository:
            class: Tests\fake\FakeAccountRepository
        Src\Domain\Repository\CategoryRepository:
            class: Tests\fake\FakeCategoryRepository
        Src\Domain\Repository\TransactionRepository:
            class: Tests\fake\FakeTransactionRepository
        Src\App\Security\TokenService:
            class: Tests\fake\FakeTokenService
        Src\Domain\Repository\SessionRepository:
            class: Tests\fake\FakeSessionRepository
```

Graças a esse desacoplamento:
1. Testes de Casos de Uso e Controllers executam **sem latência de rede ou disco**.
2. **Não é necessário subir um PostgreSQL** para rodar a suíte de testes.
3. Não ocorrem conflitos de estado de banco entre testes concorrentes.

---

### Testes com PHPUnit & Fake Repositories

O projeto utiliza **PHPUnit 12**. A estrutura de testes espelha as camadas do sistema:
* `tests/Domain/`: Testes unitários puros das entidades e regras de validação.
* `tests/App/`: Testes dos casos de uso, garantindo que o fluxo de negócio, transações e exceções funcionem corretamente.
* `tests/Infra/`: Testes funcionais dos controllers e da segurança JWT.
* `tests/fake/`: Implementações dos repositórios em array de memória.

#### Executar a Suíte Completa de Testes:
```bash
./vendor/bin/phpunit
```

#### Executar um Teste ou Diretório Específico:
```bash
# Apenas testes de Domínio
./vendor/bin/phpunit tests/Domain

# Apenas testes do Caso de Uso de Transferência
./vendor/bin/phpunit tests/App/TransferTest.php

# Apenas testes do Controller de Contas
./vendor/bin/phpunit tests/Infra/AccountControllerTest.php
```

---

## 📖 Documentação de API (Swagger / OpenAPI)

O Minty utiliza o **NelmioApiDocBundle** integrado com anotações e atributos nativos do OpenAPI PHP (`OpenApi\Attributes`):

* **Swagger UI Interativa**: `http://localhost:8000/api/doc`
* **Especificação OpenAPI em JSON**:
  ```bash
  php bin/console nelmio:apidoc:dump --format=json
  ```
* **Documentação Estática Consolidada**:
  Consulte [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md) para a lista completa de payloads, rotas e códigos de retorno de toda a aplicação.

---

## 💡 Guia Passo a Passo: Criando uma Nova Funcionalidade

Ao implementar uma nova feature no Minty, siga o fluxo de fora para dentro (ou de dentro para fora no estilo DDD):

1. **Defina a Regra no Domínio (`src/Domain`)**:
   * Adicione o método/regra na Entidade correspondente ou crie uma nova em `Domain/Entities/`.
   * Crie/ajuste os Value Objects necessários em `Domain/ValueObject/`.
   * Declare novos métodos na interface em `Domain/Repository/`.
   * Escreva o teste de domínio em `tests/Domain/`.

2. **Crie o Caso de Uso (`src/App`)**:
   * Crie o DTO em `src/App/DTO/`.
   * Crie a classe do Caso de Uso em `src/App/Usecases/` com o método `execute(MeuDto $dto)`.
   * Adicione o teste do caso de uso em `tests/App/`.
   * Se criou novos métodos no repositório, implemente-os no `FakeRepository` correspondente em `tests/fake/`.

3. **Implemente a Persistência (`src/Infra/Db`)**:
   * Ajuste o mapeamento da entidade Doctrine em `src/Infra/Db/Entity/`.
   * Atualize o `Mapper` correspondente em `src/Infra/Db/Mapper/`.
   * Implemente o método no repositório Doctrine em `src/Infra/Db/Repository/`.
   * Gere e rode a migration:
     ```bash
     php bin/console doctrine:migrations:diff
     php bin/console doctrine:migrations:migrate
     php bin/console doctrine:schema:validate
     ```

4. **Exponha o Endpoint HTTP (`src/Infra/Http`)**:
   * Crie o Schema de validação em `src/Infra/Http/Schema/`.
   * Crie o método de rota no Controller em `src/Infra/Http/Controller/` com as anotações do Swagger e `#[RequiresAuth]` (se protegido).
   * Valide via `RequestValidator` e execute o Caso de Uso.
   * Adicione o teste funcional em `tests/Infra/`.

---

## ❓ Troubleshooting & Perguntas Frequentes

### 1. `could not find driver` ao rodar migrations
**Causa:** A extensão `pdo_pgsql` do PHP não está instalada ou habilitada.  
**Solução:**
* No Ubuntu/Debian:
  ```bash
  sudo apt-get install php8.4-pgsql
  ```
* Verifique com `php -m | grep pgsql`.

### 2. `permission denied while trying to connect to the docker API`
**Causa:** Seu usuário local não tem permissão para acessar o socket `/var/run/docker.sock`.  
**Solução:**
```bash
sudo usermod -aG docker $USER
newgrp docker
# Ou execute o compose com sudo:
sudo docker compose up -d
```

### 3. As migrations acusam erro de `Metadata storage table not up to date`
**Causa:** A tabela de controle de versões do Doctrine precisa sincronizar metadados.  
**Solução:**
```bash
php bin/console doctrine:migrations:sync-metadata-storage
```

### 4. Como limpar o cache do Symfony em desenvolvimento?
```bash
php bin/console cache:clear
```

### 5. As alterações no `.env` não estão tendo efeito
**Causa:** O cache do Symfony pode ter mantido os parâmetros compilados.  
**Solução:**
```bash
php bin/console cache:clear
```
Também certifique-se de que não existe um arquivo `.env.local.php` sobrescrevendo as configurações.

---

> [!TIP]
> Em caso de dúvidas sobre contratos de rotas existentes, consulte a documentação detalhada de endpoints em [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md).
