# Minty — Frontend

Interface do Minty, uma aplicação de controle financeiro pessoal e transferências entre contas. Os textos da interface estão em inglês.

## Estado atual

O frontend está em desenvolvimento e funciona como um protótipo navegável:

- Página pública com apresentação do produto, menu responsivo e animações de entrada ao rolar.
- Layout de login e cadastro, alternados dentro de `/auth-login`.
- Dashboard com menu responsivo e páginas carregadas sob demanda.
- Dados demonstrativos de contas e transações.
- Tema compartilhado entre PrimeNG e Tailwind.

A autenticação e os formulários ainda não estão integrados à API. Não há guard de autenticação nas rotas: o dashboard pode ser acessado diretamente para visualizar o protótipo. Botões, filtros e ações financeiras ainda incluem controles apenas visuais; o link de saída navega para o login, sem invalidar uma sessão no backend.

## Tecnologias

Versões declaradas em `package.json`:

| Tecnologia | Versão |
| --- | --- |
| Angular | 22.1 |
| PrimeNG | 22.1 |
| PrimeIcons para Angular | 8 |
| PrimeUIX Themes | 3 |
| Tailwind CSS | 4.3 |
| TypeScript | 6 |
| Vitest | 4 |

Os componentes utilizam a abordagem standalone, com estilos em Tailwind e SCSS.

## Executar localmente

A partir da raiz do repositório:

```bash
cd frontend
nvm install
nvm use
npm ci
npm start
```

O arquivo `.nvmrc` fixa o Node.js em `v24.18.0`; o `package.json` declara npm `11.6.2`. Caso não use nvm, utilize a versão de Node indicada nesse arquivo.

Acesse [localhost:4200](http://localhost:4200). Para abrir o protótipo interno, acesse [localhost:4200/dashboard](http://localhost:4200/dashboard). Não é necessário iniciar o backend para visualizar os dados demonstrativos.

## Comandos

Execute dentro de `frontend/`:

| Comando | Finalidade |
| --- | --- |
| `npm start` | Servidor de desenvolvimento com recarregamento automático |
| `npm run build` | Build de produção, com otimizações e limites de tamanho |
| `npm run build -- --configuration development` | Build de desenvolvimento |
| `npm run watch` | Recompilação contínua em desenvolvimento |
| `npm test` | Executar o runner de testes unitários |
| `npm test -- --watch=false` | Executar testes sem modo de observação |
| `npm run ng -- generate component caminho/nome` | Gerar um componente |

A saída padrão do build fica em `dist/frontend/browser/`. Os testes usam o builder `@angular/build:unit-test`, com Vitest. Não há um target de testes end-to-end configurado.

A fonte Inter é carregada do Google Fonts. O build de produção pode precisar de acesso à rede para incorporar essa fonte; o build de desenvolvimento não faz essa otimização.

## Rotas

Definidas em [app.routes.ts](src/app/app.routes.ts).

| Rota | Página |
| --- | --- |
| `/` | Página pública |
| `/auth-login` | Login e cadastro |
| `/dashboard` | Visão geral |
| `/dashboard/accounts` | Contas |
| `/dashboard/accounts/new` | Nova conta |
| `/dashboard/accounts/:accountId` | Detalhes da conta |
| `/dashboard/accounts/:accountId/transactions` | Extrato |
| `/dashboard/transfer` | Transferência |
| `/dashboard/categories` | Categorias |
| `/dashboard/archived` | Itens arquivados |
| `/dashboard/profile` | Perfil |
| Demais caminhos | Página não encontrada |

As páginas de `/dashboard` são rotas filhas do `DashboardLayout`. O menu e o cabeçalho permanecem montados, enquanto o `router-outlet` exibe a página selecionada.

Exemplo com uma conta demonstrativa: `/dashboard/accounts/acc-carteira`.

## Organização

```text
frontend/
├── public/img/                       # Logo e imagens da marca
├── src/
│   ├── app/
│   │   ├── app.config.ts             # Providers e configuração PrimeNG
│   │   ├── app.routes.ts             # Rotas públicas e dashboard
│   │   ├── mypreset.ts               # Paleta e tokens do tema Aura
│   │   └── layout/
│   │       ├── public-layout/
│   │       │   ├── Components/       # Header, hero e footer
│   │       │   └── components-reveal.ts
│   │       ├── auth-layout/
│   │       │   └── components/       # Form, login, cadastro e menu mobile
│   │       ├── dashboard-layout/
│   │       │   ├── components/       # Menu compartilhado
│   │       │   ├── mock/mock-data.ts # Contas, transações e formatação monetária
│   │       │   └── pages/
│   │       │       ├── account/      # Contas, criação, detalhes e extrato
│   │       │       ├── dashboard-home.ts
│   │       │       ├── transfer.ts
│   │       │       ├── categories.ts
│   │       │       ├── archived.ts
│   │       │       └── profile.ts
│   │       └── no-page/
│   ├── styles.scss                  # Fonte e estilos globais
│   └── theme.css                    # Integração Tailwind/PrimeNG e camadas CSS
├── angular.json
└── package.json
```

A pasta `Components` do layout público usa inicial maiúscula. Preserve essa capitalização nos imports.

## Cores e tema

O [MyPreset](src/app/mypreset.ts) estende Aura e define a paleta compartilhada:

- `primary`: esmeralda, usada nos botões, links e destaques.
- `surface`: slate, usada nos fundos, bordas e textos neutros.
- Tokens de contraste, hover e estado ativo para os componentes PrimeNG.

O [theme.css](src/theme.css) importa `tailwindcss-primeui`, que disponibiliza essas cores como classes Tailwind:

```html
<section class="rounded-2xl border border-surface-200 bg-surface-0 p-6">
  <h2 class="text-surface-900">My accounts</h2>
  <p class="text-surface-500">Manage your balances.</p>
  <button class="bg-primary text-primary-contrast hover:bg-primary-emphasis">
    New account
  </button>
</section>
```

Também estão disponíveis escalas como `bg-primary-50`, `text-primary-600` e `bg-surface-100`. Para SCSS, utilize os tokens CSS, por exemplo `var(--p-primary-color)` e `var(--p-text-color)`.

A ordem das camadas é `theme, base, primeng, components, utilities`, definida também em [app.config.ts](src/app/app.config.ts). Assim, as classes utilitárias podem personalizar componentes PrimeNG sem `!important`. Evite regras globais como `* { color: ... }`, que impedem a herança correta das cores.

Para alterar a identidade visual, comece pelo preset. Os imports do Tailwind ficam em CSS separado para evitar que Sass resolva incorretamente imports internos dos pacotes.

O seletor de modo escuro do PrimeNG é `.my-app-dark`. O preset contém tokens escuros, mas não há alternador de tema e os layouts ainda precisam de adaptação completa para esse modo.

## Ícones e animações

Os ícones utilizam `@primeicons/angular`. Para nomes dinâmicos, importe `PIcon` de `@primeicons/angular/p-icon` no componente:

```html
<svg pIcon="wallet" size="20" aria-hidden="true"></svg>
```

Botões compostos apenas por um ícone devem ter um `aria-label` descritivo.

A página pública abre diretamente, sem splash screen. A diretiva `appReveal` usa `IntersectionObserver` para revelar seções uma única vez ao entrarem na área visível. Os estilos ficam em `Components/hero/hero.scss`; a animação é desativada quando o navegador solicita movimento reduzido.

## Dados e integração futura

Os dados principais do dashboard estão em [mock-data.ts](src/app/layout/dashboard-layout/mock/mock-data.ts). Algumas páginas também contêm textos, opções e indicadores demonstrativos locais.

Os valores monetários são inteiros em centavos. O helper `money()` formata os valores em inglês, mantendo a moeda BRL; a mudança de idioma não converte a moeda.

Próximas etapas de integração:

1. Criar serviços HTTP para autenticação, contas, categorias e transações.
2. Implementar sessão, interceptor e proteção das rotas do dashboard.
3. Conectar formulários e filtros, com validação e estados de carregamento, erro e lista vazia.
4. Substituir mocks e indicadores fixos por dados retornados pela API.
5. Implementar ações somente quando seus contratos estiverem disponíveis no backend.

O cancelamento de transações e a reativação de itens não estão disponíveis no protótipo. O extrato da API é por conta; uma visão consolidada depende de agregação ou de um endpoint próprio.

Consulte o [README geral](../README.md) e o [USER_EXPERIENCE.md](../USER_EXPERIENCE.md) para o domínio e os fluxos previstos.
