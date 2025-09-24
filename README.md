# agendamentohorarios

Sistema web para gestão de agendas e marcação de horários, com foco em disponibilidade, reserva, confirmação e comunicação com usuários. Projeto desenvolvido em Laravel (back-end) e Vue 3 + Vite (front-end), utilizando filas para tarefas assíncronas e MySQL como banco de dados.

- Diagrama do Banco de Dados: https://github.com/user-attachments/files/22503711/Diagrama.em.branco.pdf
- Documentação detalhada: https://docs.google.com/document/d/1VnPKu9jjnGJqkdoFHQalZ5uvimMnO69b-xxJSnQ_wlQ/edit?usp=sharing

Integrantes do Grupo: Mateus Serafim, Rian Torres e Luiz Piovisan

---

## Sumário
- Visão geral
- Stack e requisitos
- Arquitetura do projeto
- Design patterns adotados
- Como rodar o projeto (Local e Docker/Sail)
- Front-end (Vue 3 + Vite)
- Convenções e fluxo de Git

---

## Visão geral
O sistema permite:
- Cadastro e gerenciamento de agendas e horários disponíveis.
- Reserva/Agendamento de horários por usuários.
- Regras de conflito/validação para evitar sobreposições.
- Notificações.

Objetivo: oferecer um fluxo simples e robusto para criar agendas, disponibilizar horários e permitir que usuários realizem e acompanhem seus agendamentos.

---

## Stack e requisitos

Back-end:
- PHP 8.2+
- Laravel 12.x
- MySQL 8.x

Front-end:
- Vue 3
- TypeScript
- Vite
- Tailwind CSS

Ferramentas adicionais:
- Composer 2.x
- Node.js 20.x + npm

---

## Arquitetura do projeto

Arquitetura base: MVC (Model–View–Controller), conforme recomenda o framework Laravel.

- Model (M)
  - Modelos Eloquent representam e persistem os dados.
  - Regras simples de domínio (casts, mutators) podem residir aqui; regras complexas ficam em Services.
- View (V)
  - Interface do usuário via Inertia/Vue 3 servidos pelo Vite.
  - Componentização e reutilização de UI no front-end.
- Controller (C)
  - Recebe requisições HTTP, delega para Services/Use Cases, aplica validações com Form Requests e retorna Resources (JSON) ou Views.

Camadas complementares:
- Services/Use Cases: encapsulam fluxos de negócio reutilizáveis (podem ser chamados por Controllers e Jobs).

Diretrizes práticas:
- Controllers finos; lógica de negócio em Services.
- Paginação padrão em listagens; ordenação e filtros explícitos via query params.

---

## Design patterns adotados

- Service Layer
  - Orquestra regras de negócio, mantendo Controllers finos e permitindo reutilização por Jobs.
  - Útil para consolidar validações e políticas de agendamento em um único ponto.

- Repository Pattern
  - Abstrai a persistência (Eloquent) por meio de interfaces e implementações concretas.
  - Facilita troca de fonte de dados, mock em testes e separação de responsabilidades.

Boas práticas:
- Regra de negócio fora dos Models; Models focados em persistência/relacionamentos.
- Testes de Services com mocks de Repositórios; validação de Resources por snapshots.

---

## Como rodar o projeto

Pré-requisitos:
- PHP 8.2+, Composer
- MySQL em execução
- Node.js 20+ e npm
- Extensões comuns do PHP habilitadas (pdo, mbstring, etc.)

1) Clonar e instalar dependências
- git clone <url-do-repo>
- cd agendamentohorarios
- cp .env.example .env
- composer install
- npm install

2) Configurar .env
- APP_KEY (gerar com o comando abaixo)
- DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- QUEUE_CONNECTION=database
- MAIL_* se for utilizar envio de e-mails

3) Gerar chave e preparar banco
- php artisan key:generate
- Caso necessário para filas com driver database: php artisan queue:table
- php artisan migrate --seed (se houver seeders)

4) Subir a aplicação
- Servidor HTTP: php artisan serve
- Vite (front-end): npm run dev
- Worker de filas: php artisan queue:work
- Agendador (scheduler) em desenvolvimento local: php artisan schedule:work
  - Em produção, use cron: * * * * * php /caminho/para/artisan schedule:run >> /dev/null 2>&1

Acesso:
- Aplicação: http://localhost:8000
- Vite (quando aplicável): http://localhost:5173

### Rodando com Docker (Laravel Sail) — opcional
- composer install
- cp .env.example .env
- Ajuste variáveis do .env para o Sail (DB, QUEUE_CONNECTION=database etc.)
- php artisan key:generate
- ./vendor/bin/sail up -d
- ./vendor/bin/sail artisan migrate --seed
- Front-end: ./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
- Filas: ./vendor/bin/sail artisan queue:work
- Scheduler: configure cron no container ou use ./vendor/bin/sail artisan schedule:work

---

## Front-end (Vue 3 + Vite)

Scripts úteis:
- Desenvolvimento: npm run dev
- Build de produção: npm run build
- Lint/format: conforme configuração do ESLint/Prettier

Diretrizes:
- Componentização e reuso; manter estados locais simples.
- Comunicação com back-end via rotas/API do Laravel.
- Padronizar estilos com Tailwind; evitar CSS global desnecessário.

---

## Convenções e fluxo de Git

Branches:
- main: estável/produção
- develop: integração/estágio
- feat/<descricao-curta>: novas funcionalidades
- fix/<descricao-curta>: correções
- hotfix/<descricao-curta>: correções urgentes em produção
- release/<versão>: preparação de release

Pull Requests:
- Basear em dev (ou conforme fluxo).
- Descrever objetivo, mudanças e evidências.
- Exigir pelo menos 1 review.

Conventional Commits:
- feat: nova funcionalidade
- fix: correção de bug
- docs: apenas documentação
- style: formatação, sem lógica
- refactor: refatoração sem mudança de comportamento
- perf: melhora de performance
- test: adiciona/ajusta testes
- build: mudanças de build/deps
- ci: ajustes de CI
- chore: tarefas diversas
- revert: reverte commit anterior

.gitignore:
- Baseado no padrão do Laravel (ex.: /vendor, /node_modules, .env, storage/* exceto necessários).

---

## Contato e suporte

- Dúvidas e problemas: abrir Issues com descrição, passos para reproduzir e evidências.
- Segurança: para vulnerabilidades, preferir contato privado com os mantenedores antes de abrir issue pública.