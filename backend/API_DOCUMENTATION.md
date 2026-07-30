# 📘 Minty API — Documentação Completa

> **Base URL:** `http://localhost:8000` (ou conforme configurado)
> **Formato:** `application/json`
> **Autenticação:** Bearer Token (JWT via header `Authorization: Bearer <token>`)
> **Refresh Token:** Enviado automaticamente via Cookie `HttpOnly` (`refresh_token`)

---

## 📋 Índice

- [Autenticação](#-autenticação)
  - [POST /login](#post-login)
  - [POST /logout](#post-logout)
  - [POST /refresh](#post-refresh)
- [Usuários](#-usuários)
  - [POST /users](#post-users)
  - [GET /users/me](#get-usersme)
  - [GET /users/email/{email}](#get-usersemailemail)
  - [GET /users/search](#get-userssearch)
  - [PATCH /users/email](#patch-usersemail)
  - [PATCH /users/password](#patch-userspassword)
  - [PATCH /users/name](#patch-usersname)
- [Contas](#-contas)
  - [POST /accounts](#post-accounts)
  - [GET /accounts](#get-accounts)
  - [GET /accounts/{accountId}](#get-accountsaccountid)
  - [PATCH /accounts/{accountId}](#patch-accountsaccountid)
  - [DELETE /accounts/{accountId}](#delete-accountsaccountid)
  - [POST /accounts/{accountId}/deposit](#post-accountsaccountiddeposit)
  - [POST /accounts/{accountId}/withdraw](#post-accountsaccountidwithdraw)
  - [POST /accounts/{accountId}/transfer](#post-accountsaccountidtransfer)
  - [GET /accounts/{accountId}/transactions](#get-accountsaccountidtransactions)
- [Categorias](#-categorias)
  - [POST /categories](#post-categories)
  - [GET /categories](#get-categories)
  - [PATCH /categories/{categoryId}](#patch-categoriescategoryid)
  - [DELETE /categories/{categoryId}](#delete-categoriescategoryid)
- [Padrão de Respostas](#-padrão-de-respostas)
- [Códigos de Erro](#-códigos-de-erro)

---

## 🔐 Autenticação

### POST /login

Autentica um usuário e retorna os tokens de acesso e refresh.

- **Requer autenticação:** ❌

#### Request Body

```json
{
  "email": "user@example.com",
  "password": "senha123"
}
```

| Campo      | Tipo   | Obrigatório | Descrição                      |
|------------|--------|:-----------:|-------------------------------|
| `email`    | string | ✅          | E-mail válido do usuário      |
| `password` | string | ✅          | Senha do usuário (min. 8 chars)|

#### Respostas

**200 OK — Login realizado com sucesso**
```json
{
  "success": true,
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "refresh": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  },
  "message": "Login Successful"
}
```

> ⚠️ Um cookie `HttpOnly` chamado `refresh_token` também é definido automaticamente na resposta (validade de 7 dias).

**400 Bad Request — Erro de validação**
```json
{
  "error": true,
  "code": "VALIDATION_ERROR",
  "message": "Email is required"
}
```

**401 Unauthorized — Senha incorreta**
```json
{
  "error": true,
  "code": "WRONG_PASSWORD",
  "message": "Wrong password"
}
```

**404 Not Found — Usuário não encontrado**
```json
{
  "error": true,
  "code": "EMAIL_NOT_FOUND",
  "message": "User not found"
}
```

---

### POST /logout

Invalida a sessão do usuário e limpa o cookie de refresh token.

- **Requer autenticação:** ✅ (Bearer Token)
- **Requer Cookie:** `refresh_token`

#### Request Body

_Nenhum corpo de requisição necessário._

#### Respostas

**204 No Content — Logout realizado com sucesso**

```
(sem corpo de resposta)
```

**400 Bad Request — Cookie de refresh token ausente**
```json
{
  "error": true,
  "code": "COOKIE_ERROR",
  "message": "Refresh token not found in cookies"
}
```

---

### POST /refresh

Renova o access token utilizando o refresh token do cookie.

- **Requer autenticação:** ❌
- **Requer Cookie:** `refresh_token`

#### Request Body

_Nenhum corpo de requisição necessário._

#### Respostas

**200 OK — Token renovado com sucesso**
```json
{
  "success": true,
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "refresh": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  },
  "message": "Token Refreshed"
}
```

> ⚠️ Um novo cookie `refresh_token` é redefinido automaticamente.

**400 Bad Request — Refresh token inválido ou ausente**
```json
{
  "error": true,
  "code": "INVALID_REFRESH_TOKEN",
  "message": "Invalid refresh token"
}
```

**404 Not Found — Sessão não encontrada**
```json
{
  "error": true,
  "code": "SESSION_NOT_FOUND",
  "message": "Session not found"
}
```

---

## 👤 Usuários

### POST /users

Cria um novo usuário no sistema.

- **Requer autenticação:** ❌

#### Request Body

```json
{
  "name": "João Silva",
  "email": "joao@exemplo.com",
  "password": "MinhaSenh@123"
}
```

| Campo      | Tipo   | Obrigatório | Validações                                          |
|------------|--------|:-----------:|----------------------------------------------------|
| `name`     | string | ✅          | Mínimo 3 caracteres                                |
| `email`    | string | ✅          | Formato de e-mail válido                           |
| `password` | string | ✅          | Mínimo 8 caracteres, força mínima de senha (score ≥ 1) |

#### Respostas

**201 Created — Usuário criado com sucesso**
```json
{
  "success": true,
  "data": null,
  "message": "User Created"
}
```

**400 Bad Request — Erro de validação**
```json
{
  "error": true,
  "code": "VALIDATION_ERROR",
  "message": "Name must have at least 3 characters"
}
```

**409 Conflict — E-mail já em uso**
```json
{
  "error": true,
  "code": "EMAIL_ALREADY_IN_USE",
  "message": "Email already in use"
}
```

---

### GET /users/me

Retorna os dados do usuário autenticado.

- **Requer autenticação:** ✅ (Bearer Token)

#### Respostas

**200 OK — Usuário encontrado**
```json
{
  "success": true,
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "name": "João Silva",
    "email": "joao@exemplo.com"
  },
  "message": "User Found"
}
```

**401 Unauthorized — Token inválido ou ausente**
```json
{
  "error": true,
  "code": 401,
  "message": "Token Null"
}
```

**404 Not Found — Usuário não encontrado**
```json
{
  "error": true,
  "code": "USER_NOT_FOUND",
  "message": "User not found"
}
```

---

### GET /users/email/{email}

Busca um usuário específico pelo e-mail (busca exata via path param).

- **Requer autenticação:** ❌

#### Path Parameters

| Parâmetro | Tipo   | Obrigatório | Descrição         |
|-----------|--------|:-----------:|------------------|
| `email`   | string | ✅          | E-mail do usuário|

#### Exemplo

```
GET /users/email/joao@exemplo.com
```

#### Respostas

**200 OK — Usuário encontrado**
```json
{
  "success": true,
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "name": "João Silva",
    "email": "joao@exemplo.com"
  },
  "message": "User Found"
}
```

**404 Not Found — Usuário não encontrado**
```json
{
  "error": true,
  "code": "EMAIL_NOT_FOUND",
  "message": "User not found"
}
```

---

### GET /users/search

Busca usuários por prefixo de e-mail (busca parcial via query param).

- **Requer autenticação:** ❌

#### Query Parameters

| Parâmetro | Tipo   | Obrigatório | Descrição                    |
|-----------|--------|:-----------:|------------------------------|
| `email`   | string | ✅          | Prefixo ou trecho do e-mail  |

#### Exemplo

```
GET /users/search?email=joao
```

#### Respostas

**200 OK — Usuários encontrados**
```json
{
  "success": true,
  "data": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440000",
      "name": "João Silva",
      "email": "joao@exemplo.com"
    }
  ],
  "message": "Users Found"
}
```

**400 Bad Request — Parâmetro de busca ausente**
```json
{
  "error": true,
  "code": "QUERY_ERROR",
  "message": "Search term is required"
}
```

---

### PATCH /users/email

Atualiza o e-mail do usuário autenticado. Requer confirmação de senha.

- **Requer autenticação:** ✅ (Bearer Token)

#### Request Body

```json
{
  "email": "novo-email@exemplo.com",
  "password": "MinhaSenh@123"
}
```

| Campo      | Tipo   | Obrigatório | Descrição                             |
|------------|--------|:-----------:|---------------------------------------|
| `email`    | string | ✅          | Novo e-mail válido (diferente do atual)|
| `password` | string | ✅          | Senha atual para confirmação          |

#### Respostas

**200 OK — E-mail atualizado**
```json
{
  "success": true,
  "data": null,
  "message": "Email Updated"
}
```

**403 Forbidden — Senha não confere**
```json
{
  "error": true,
  "code": "PASSWORD_DOES_NOT_MATCH",
  "message": "Password does not match"
}
```

**409 Conflict — E-mail já em uso ou igual ao atual**
```json
{
  "error": true,
  "code": "EMAIL_ALREADY_IN_USE",
  "message": "Email already in use"
}
```

---

### PATCH /users/password

Atualiza a senha do usuário.

- **Requer autenticação:** ✅ (Bearer Token)

#### Query Parameters

| Parâmetro | Tipo   | Obrigatório | Descrição         |
|-----------|--------|:-----------:|------------------|
| `email`   | string | ✅          | E-mail do usuário|

#### Request Body

```json
{
  "oldPassword": "SenhaAntiga@123",
  "newPassword": "NovaSenha@456"
}
```

| Campo         | Tipo   | Obrigatório | Validações                           |
|---------------|--------|:-----------:|--------------------------------------|
| `oldPassword` | string | ✅          | Senha atual (mín. 8 chars, força ≥ 1)|
| `newPassword` | string | ✅          | Nova senha (mín. 8 chars, força ≥ 1) |

#### Exemplo de Request

```
PATCH /users/password?email=joao@exemplo.com
```

#### Respostas

**200 OK — Senha atualizada**
```json
{
  "success": true,
  "data": null,
  "message": "Password Updated"
}
```

**401 Unauthorized — Senha antiga incorreta**
```json
{
  "error": true,
  "code": "WRONG_PASSWORD",
  "message": "Wrong password"
}
```

**422 Unprocessable Entity — Senha nova muito fraca**
```json
{
  "error": true,
  "code": "WEAK_PASSWORD",
  "message": "Password is too weak"
}
```

---

### PATCH /users/name

Atualiza o nome do usuário.

- **Requer autenticação:** ✅ (Bearer Token)

#### Query Parameters

| Parâmetro | Tipo   | Obrigatório | Descrição         |
|-----------|--------|:-----------:|------------------|
| `email`   | string | ✅          | E-mail do usuário|

#### Request Body

```json
{
  "name": "João da Silva Sauro"
}
```

| Campo  | Tipo   | Obrigatório | Validações         |
|--------|--------|:-----------:|--------------------|
| `name` | string | ✅          | Não pode ser vazio |

#### Exemplo de Request

```
PATCH /users/name?email=joao@exemplo.com
```

#### Respostas

**200 OK — Nome atualizado**
```json
{
  "success": true,
  "data": null,
  "message": "Name updated"
}
```

**400 Bad Request — Erro de validação**
```json
{
  "error": true,
  "code": "VALIDATION_ERROR",
  "message": "Name cannot be null"
}
```

---

## 💰 Contas

### POST /accounts

Cria uma nova conta financeira para o usuário autenticado.

- **Requer autenticação:** ✅ (Bearer Token)

#### Request Body

```json
{
  "name": "Conta Poupança",
  "balance": 5000
}
```

| Campo     | Tipo    | Obrigatório | Descrição                               |
|-----------|---------|:-----------:|-----------------------------------------|
| `name`    | string  | ✅          | Nome da conta                           |
| `balance` | integer | ✅          | Saldo inicial (em centavos ou inteiro)  |

#### Respostas

**201 Created — Conta criada com sucesso**
```json
{
  "success": true,
  "data": null,
  "message": "Account created successfully"
}
```

**400 Bad Request — Erro de validação**
```json
{
  "error": true,
  "code": "VALIDATION_ERROR",
  "message": "Name is required"
}
```

**401 Unauthorized — Token inválido ou ausente**
```json
{
  "error": true,
  "code": 401,
  "message": "Token Null"
}
```

---

### GET /accounts

Lista todas as contas do usuário autenticado.

- **Requer autenticação:** ✅ (Bearer Token)

#### Respostas

**200 OK — Lista de contas retornada**
```json
{
  "success": true,
  "data": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440001",
      "name": "Conta Corrente",
      "balance": 15000,
      "isActive": true
    },
    {
      "id": "550e8400-e29b-41d4-a716-446655440002",
      "name": "Conta Poupança",
      "balance": 80000,
      "isActive": true
    }
  ],
  "message": "Accounts retrieved successfully"
}
```

**401 Unauthorized**
```json
{
  "error": true,
  "code": 401,
  "message": "Token Null"
}
```

---

### GET /accounts/{accountId}

Retorna os detalhes de uma conta específica.

- **Requer autenticação:** ✅ (Bearer Token)

#### Path Parameters

| Parâmetro   | Tipo | Obrigatório | Descrição             |
|-------------|------|:-----------:|-----------------------|
| `accountId` | uuid | ✅          | ID (UUID v4) da conta |

#### Exemplo

```
GET /accounts/550e8400-e29b-41d4-a716-446655440001
```

#### Respostas

**200 OK — Conta encontrada**
```json
{
  "success": true,
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440001",
    "name": "Conta Corrente",
    "balance": 15000,
    "userId": "550e8400-e29b-41d4-a716-446655440000"
  },
  "message": "Account retrieved successfully"
}
```

**400 Bad Request — ID inválido**
```json
{
  "error": true,
  "code": "PARAMS_ERROR",
  "message": "Account ID is required"
}
```

**404 Not Found — Conta não encontrada**
```json
{
  "error": true,
  "code": "ACCOUNT_NOT_FOUND",
  "message": "Account not found"
}
```

---

### PATCH /accounts/{accountId}

Atualiza o nome de uma conta.

- **Requer autenticação:** ✅ (Bearer Token)

#### Path Parameters

| Parâmetro   | Tipo | Obrigatório | Descrição   |
|-------------|------|:-----------:|-------------|
| `accountId` | uuid | ✅          | ID da conta |

#### Request Body

```json
{
  "name": "Conta Principal"
}
```

| Campo  | Tipo   | Obrigatório | Descrição          |
|--------|--------|:-----------:|--------------------|
| `name` | string | ✅          | Novo nome da conta |

#### Respostas

**200 OK — Conta atualizada**
```json
{
  "success": true,
  "data": null,
  "message": "Account updated successfully"
}
```

**404 Not Found — Conta não encontrada**
```json
{
  "error": true,
  "code": "ACCOUNT_NOT_FOUND",
  "message": "Account not found"
}
```

---

### DELETE /accounts/{accountId}

Desativa (soft delete) uma conta financeira.

- **Requer autenticação:** ✅ (Bearer Token)

#### Path Parameters

| Parâmetro   | Tipo | Obrigatório | Descrição   |
|-------------|------|:-----------:|-------------|
| `accountId` | uuid | ✅          | ID da conta |

#### Respostas

**200 OK — Conta desativada**
```json
{
  "success": true,
  "data": null,
  "message": "Account deactivated successfully"
}
```

**404 Not Found — Conta não encontrada**
```json
{
  "error": true,
  "code": "ACCOUNT_NOT_FOUND",
  "message": "Account not found"
}
```

---

### POST /accounts/{accountId}/deposit

Realiza um depósito em uma conta.

- **Requer autenticação:** ✅ (Bearer Token)

#### Path Parameters

| Parâmetro   | Tipo | Obrigatório | Descrição          |
|-------------|------|:-----------:|--------------------|
| `accountId` | uuid | ✅          | ID da conta destino|

#### Request Body

```json
{
  "amount": 2500,
  "categoryId": "a3bb189e-8bf9-3888-9912-ace4e6543002",
  "description": "Salário de Julho"
}
```

| Campo        | Tipo    | Obrigatório | Validações                     |
|--------------|---------|:-----------:|-------------------------------|
| `amount`     | integer | ✅          | Valor numérico positivo        |
| `categoryId` | uuid    | ✅          | UUID v4 de uma categoria ativa |
| `description`| string  | ❌          | Máximo 255 caracteres          |

#### Respostas

**200 OK — Depósito realizado**
```json
{
  "success": true,
  "data": null,
  "message": "Deposit successful"
}
```

**400 Bad Request — Erro de validação**
```json
{
  "error": true,
  "code": "VALIDATION_ERROR",
  "message": "Amount is required"
}
```

**404 Not Found — Conta ou categoria não encontrada**
```json
{
  "error": true,
  "code": "ACCOUNT_NOT_FOUND",
  "message": "Account not found"
}
```

**409 Conflict — Categoria inativa**
```json
{
  "error": true,
  "code": "CATEGORY_INACTIVE",
  "message": "Category is inactive"
}
```

---

### POST /accounts/{accountId}/withdraw

Realiza um saque de uma conta.

- **Requer autenticação:** ✅ (Bearer Token)

#### Path Parameters

| Parâmetro   | Tipo | Obrigatório | Descrição         |
|-------------|------|:-----------:|-------------------|
| `accountId` | uuid | ✅          | ID da conta origem|

#### Request Body

```json
{
  "amount": 100,
  "categoryId": "a3bb189e-8bf9-3888-9912-ace4e6543002",
  "description": "Compras no supermercado"
}
```

| Campo        | Tipo    | Obrigatório | Validações                     |
|--------------|---------|:-----------:|-------------------------------|
| `amount`     | integer | ✅          | Valor numérico positivo        |
| `categoryId` | uuid    | ✅          | UUID v4 de uma categoria ativa |
| `description`| string  | ❌          | Máximo 255 caracteres          |

#### Respostas

**200 OK — Saque realizado**
```json
{
  "success": true,
  "data": null,
  "message": "Withdraw successful"
}
```

**404 Not Found — Conta ou categoria não encontrada**
```json
{
  "error": true,
  "code": "ACCOUNT_NOT_FOUND",
  "message": "Account not found"
}
```

**422 Unprocessable Entity — Saldo insuficiente**
```json
{
  "error": true,
  "code": "INSUFFICIENT_FUNDS",
  "message": "Insufficient funds"
}
```

---

### POST /accounts/{accountId}/transfer

Realiza uma transferência entre duas contas.

- **Requer autenticação:** ✅ (Bearer Token)

#### Path Parameters

| Parâmetro   | Tipo | Obrigatório | Descrição          |
|-------------|------|:-----------:|--------------------|
| `accountId` | uuid | ✅          | ID da conta origem |

#### Request Body

```json
{
  "toAccountId": "a3bb189e-8bf9-3888-9912-ace4e6543099",
  "amount": 1500,
  "categoryId": "a3bb189e-8bf9-3888-9912-ace4e6543002",
  "description": "Aluguel"
}
```

| Campo         | Tipo    | Obrigatório | Validações                      |
|---------------|---------|:-----------:|---------------------------------|
| `toAccountId` | uuid    | ✅          | UUID v4 da conta destino        |
| `amount`      | integer | ✅          | Valor numérico positivo         |
| `categoryId`  | uuid    | ✅          | UUID v4 de uma categoria ativa  |
| `description` | string  | ❌          | Máximo 255 caracteres           |

#### Respostas

**200 OK — Transferência realizada**
```json
{
  "success": true,
  "data": null,
  "message": "Transfer successful"
}
```

**400 Bad Request — Transferência inválida (ex.: conta origem = destino)**
```json
{
  "error": true,
  "code": "INVALID_TRANSFER",
  "message": "Cannot transfer to the same account"
}
```

**404 Not Found — Conta origem ou destino não encontrada**
```json
{
  "error": true,
  "code": "ACCOUNT_NOT_FOUND",
  "message": "Account not found"
}
```

---

### GET /accounts/{accountId}/transactions

Lista o extrato de transações de uma conta com filtro opcional por status.

- **Requer autenticação:** ✅ (Bearer Token)

#### Path Parameters

| Parâmetro   | Tipo | Obrigatório | Descrição   |
|-------------|------|:-----------:|-------------|
| `accountId` | uuid | ✅          | ID da conta |

#### Query Parameters

| Parâmetro | Tipo   | Obrigatório | Valores aceitos                |
|-----------|--------|:-----------:|-------------------------------|
| `status`  | string | ❌          | `DONE`, `PENDING`, `CANCELLED`|

#### Exemplo

```
GET /accounts/550e8400-e29b-41d4-a716-446655440001/transactions?status=DONE
```

#### Respostas

**200 OK — Transações retornadas**
```json
{
  "success": true,
  "data": [
    {
      "id": "7f8c9d0e-1234-5678-abcd-ef0123456789",
      "accountId": "550e8400-e29b-41d4-a716-446655440001",
      "amount": 2500,
      "createdAt": "2026-07-30T14:30:00-03:00",
      "type": "DEPOSIT",
      "status": "DONE",
      "description": "Salário de Julho",
      "categoryId": "a3bb189e-8bf9-3888-9912-ace4e6543002"
    },
    {
      "id": "1a2b3c4d-5678-90ab-cdef-012345678901",
      "accountId": "550e8400-e29b-41d4-a716-446655440001",
      "amount": 100,
      "createdAt": "2026-07-29T10:00:00-03:00",
      "type": "WITHDRAW",
      "status": "DONE",
      "description": "Compras no supermercado",
      "categoryId": "a3bb189e-8bf9-3888-9912-ace4e6543003"
    }
  ],
  "message": "Transactions retrieved successfully"
}
```

> **Tipos de transação (`type`):** `DEPOSIT`, `WITHDRAW`, `TRANSFER`
> **Status possíveis (`status`):** `DONE`, `PENDING`, `CANCELLED`

**404 Not Found — Conta não encontrada**
```json
{
  "error": true,
  "code": "ACCOUNT_NOT_FOUND",
  "message": "Account not found"
}
```

---

## 🏷️ Categorias

### POST /categories

Cria uma nova categoria de transação para o usuário autenticado.

- **Requer autenticação:** ✅ (Bearer Token)

#### Request Body

```json
{
  "name": "Alimentação",
  "description": "Supermercado e restaurantes"
}
```

| Campo         | Tipo   | Obrigatório | Descrição                      |
|---------------|--------|:-----------:|-------------------------------|
| `name`        | string | ✅          | Nome da categoria              |
| `description` | string | ❌          | Descrição opcional da categoria|

#### Respostas

**201 Created — Categoria criada**
```json
{
  "success": true,
  "data": null,
  "message": "Category created successfully"
}
```

**400 Bad Request — Erro de validação**
```json
{
  "error": true,
  "code": "VALIDATION_ERROR",
  "message": "Name is required"
}
```

**404 Not Found — Usuário não encontrado**
```json
{
  "error": true,
  "code": "USER_NOT_FOUND",
  "message": "User not found"
}
```

---

### GET /categories

Lista todas as categorias do usuário autenticado com filtro opcional por status.

- **Requer autenticação:** ✅ (Bearer Token)

#### Query Parameters

| Parâmetro  | Tipo    | Obrigatório | Descrição                                 |
|------------|---------|:-----------:|------------------------------------------|
| `isActive` | boolean | ❌          | `true` para ativas, `false` para inativas|

#### Exemplo

```
GET /categories?isActive=true
```

#### Respostas

**200 OK — Categorias retornadas**
```json
{
  "success": true,
  "data": [
    {
      "id": "a3bb189e-8bf9-3888-9912-ace4e6543002",
      "name": "Alimentação",
      "description": "Supermercado e restaurantes",
      "isActive": true
    },
    {
      "id": "a3bb189e-8bf9-3888-9912-ace4e6543003",
      "name": "Transporte",
      "description": null,
      "isActive": true
    }
  ],
  "message": "Categories retrieved successfully"
}
```

**401 Unauthorized**
```json
{
  "error": true,
  "code": 401,
  "message": "Token Null"
}
```

---

### PATCH /categories/{categoryId}

Atualiza o nome e/ou descrição de uma categoria. Ao menos um campo deve ser fornecido.

- **Requer autenticação:** ✅ (Bearer Token)

#### Path Parameters

| Parâmetro    | Tipo | Obrigatório | Descrição       |
|--------------|------|:-----------:|-----------------|
| `categoryId` | uuid | ✅          | ID da categoria |

#### Request Body

```json
{
  "name": "Alimentação & Bebidas",
  "description": "Supermercado, restaurantes e delivery"
}
```

| Campo         | Tipo   | Obrigatório | Descrição                              |
|---------------|--------|:-----------:|---------------------------------------|
| `name`        | string | ❌*         | Novo nome da categoria                |
| `description` | string | ❌*         | Nova descrição da categoria           |

> *Ao menos um dos campos deve ser enviado.

#### Respostas

**200 OK — Categoria atualizada**
```json
{
  "success": true,
  "data": null,
  "message": "Category updated successfully"
}
```

**400 Bad Request — Nenhum campo enviado para atualização**
```json
{
  "error": true,
  "code": "NEED_TO_UPDATE_AT_LEAST_ONE_FIELD",
  "message": "You need to update at least one field"
}
```

**404 Not Found — Categoria não encontrada**
```json
{
  "error": true,
  "code": "CATEGORY_NOT_FOUND",
  "message": "Category not found"
}
```

---

### DELETE /categories/{categoryId}

Desativa (soft delete) uma categoria. Categorias inativas não podem ser usadas em novas transações.

- **Requer autenticação:** ✅ (Bearer Token)

#### Path Parameters

| Parâmetro    | Tipo | Obrigatório | Descrição       |
|--------------|------|:-----------:|-----------------|
| `categoryId` | uuid | ✅          | ID da categoria |

#### Respostas

**204 No Content — Categoria desativada com sucesso**

```
(sem corpo de resposta)
```

**404 Not Found — Categoria não encontrada**
```json
{
  "error": true,
  "code": "CATEGORY_NOT_FOUND",
  "message": "Category not found"
}
```

**409 Conflict — Categoria já está inativa**
```json
{
  "error": true,
  "code": "CATEGORY_ALREADY_INACTIVE",
  "message": "Category already inactive"
}
```

---

## 📦 Padrão de Respostas

Todas as respostas seguem um dos três formatos abaixo:

### Sucesso (2xx)

```json
{
  "success": true,
  "data": { ... },
  "message": "Mensagem descritiva"
}
```

### Criação (201)

```json
{
  "success": true,
  "data": null,
  "message": "Recurso criado com sucesso"
}
```

### Sem conteúdo (204)

```
(sem corpo de resposta)
```

### Erro (4xx / 5xx)

```json
{
  "error": true,
  "code": "CODIGO_DO_ERRO",
  "message": "Descrição do erro"
}
```

---

## 🚨 Códigos de Erro

| Código                          | HTTP | Descrição                                         |
|---------------------------------|------|---------------------------------------------------|
| `VALIDATION_ERROR`              | 400  | Dados da requisição inválidos ou ausentes         |
| `PARAMS_ERROR`                  | 400  | Parâmetros de URL inválidos ou ausentes           |
| `QUERY_ERROR`                   | 400  | Query parameters inválidos ou ausentes            |
| `COOKIE_ERROR`                  | 400  | Cookie `refresh_token` ausente                   |
| `INVALID_REFRESH_TOKEN`         | 400  | Refresh token inválido                            |
| `INVALID_TRANSFER`              | 400  | Transferência inválida (ex.: mesma conta)         |
| `WRONG_PASSWORD`                | 401  | Senha incorreta                                   |
| `PASSWORD_DOES_NOT_MATCH`       | 403  | Senha de confirmação não confere                  |
| `EMAIL_NOT_FOUND`               | 404  | E-mail não cadastrado                             |
| `USER_NOT_FOUND`                | 404  | Usuário não encontrado                            |
| `ACCOUNT_NOT_FOUND`             | 404  | Conta não encontrada                              |
| `CATEGORY_NOT_FOUND`            | 404  | Categoria não encontrada                          |
| `SESSION_NOT_FOUND`             | 404  | Sessão não encontrada (refresh token expirado)    |
| `EMAIL_ALREADY_IN_USE`          | 409  | E-mail já cadastrado no sistema                   |
| `CATEGORY_INACTIVE`             | 409  | Categoria está inativa                            |
| `CATEGORY_ALREADY_INACTIVE`     | 409  | Categoria já estava inativa                       |
| `INSUFFICIENT_FUNDS`            | 422  | Saldo insuficiente para realizar a operação       |
| `WEAK_PASSWORD`                 | 422  | Nova senha não atende critérios mínimos           |

---

## 🛠️ Stack Técnica

| Tecnologia         | Versão | Uso                               |
|--------------------|--------|-----------------------------------|
| PHP                | 8.x    | Linguagem principal               |
| Symfony            | 7.x    | Framework HTTP / DI / Routing     |
| Doctrine ORM       | 3.x    | Persistência de dados             |
| PostgreSQL         | 16     | Banco de dados relacional         |
| JWT                | —      | Autenticação stateless            |
| NelmioApiDocBundle | —      | Geração de documentação OpenAPI   |

---

## 🔑 Configuração de Ambiente

```env
APP_SECRET=your_app_secret
JWT_SECRET=your_jwt_secret
DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=16&charset=utf8"
```

---

*Documentação gerada em 30/07/2026 — Minty Backend API v1*
