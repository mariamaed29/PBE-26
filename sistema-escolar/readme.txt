DOCUMENTAÇÃO — SISTEMA ESCOLAR (SAFE)

1) Visão Geral
O “Sistema Escolar (SAFE)” é uma aplicação web desenvolvida em Laravel para controlar ocorrências acadêmicas relacionadas a alunos, com fluxo de aprovação/validação por diferentes perfis.

Perfis (roles):
- AQV: registra e aprova/nega ocorrências; gerencia alunos.
- Portaria: confirma fisicamente a entrada/saída do aluno após a aprovação da AQV.
- Professor: visualiza notificações recebidas sobre ocorrências.

Fluxo resumido:
1. AQV registra uma ocorrência para um aluno.
2. A ocorrência é criada com status “pendente” e dispara notificações para o professor responsável (e e-mail, quando configurado).
3. AQV aprova ou nega a ocorrência.
4. Se aprovada, Portaria recebe notificações e depois confirma a ocorrência (registrando data/hora e quem confirmou).
5. Professor acessa “Notificações” e marca como lidas. Um badge no menu mostra a contagem de não lidas.

2) Stack Tecnológica e Estrutura
- Backend: Laravel (PHP)
- Autenticação: Laravel Breeze (rotas em routes/auth.php)
- Frontend:
  - Blade templates (resources/views)
  - Tailwind CSS
  - Vite para build (vite.config.js)
- Banco de dados: tabelas via migrations do Laravel
- Camada de serviços:
  - OcorrenciaService (registra/atualiza ocorrência e orquestra notificações)
  - NotificacaoService (cria registro de Notificação e envia e-mail ao professor)

3) Módulos Principais
3.1 Dashboard
Endpoint:
- GET /dashboard

Implementação:
- Controller: DashboardController
- Renderização de uma view específica por role:
  - dashboard.aqv (resources/views/dashboard/aQv.blade.php)
  - dashboard.portaria (resources/views/dashboard/portaria.blade.php)
  - dashboard.professor (resources/views/dashboard/professor.blade.php)

3.2 Alunos (CRUD)
Recurso:
- resource “alunos” (GET/POST/PATCH/DELETE conforme Laravel Resource)

Controller:
- AlunoController

Regras de acesso:
- Apenas AQV gerencia alunos (create/store/edit/update/destroy).
- Alunos são listados/visualizados também por outros perfis (index/show não sofrem bloqueio no Controller via middleware).

Páginas:
- resources/views/alunos/index.blade.php
- resources/views/alunos/create.blade.php
- resources/views/alunos/edit.blade.php
- resources/views/alunos/show.blade.php

3.3 Ocorrências (Registro + Aprovação + Confirmação)
Recurso:
- resource “ocorrencias” apenas parcialmente:
  - index, create, store, show

Ações adicionais:
- POST /ocorrencias/{ocorrencia}/aprovar (AQV)
- POST /ocorrencias/{ocorrencia}/negar (AQV)
- POST /ocorrencias/{ocorrencia}/confirmar-portaria (Portaria)

Controller:
- OcorrenciaController

Regra por role (no Controller e middleware):
- Criação (create/store): apenas AQV.
- Aprovar/Negar: apenas AQV.
- Confirmar portaria: apenas Portaria.

Views:
- resources/views/ocorrencias/index.blade.php
- resources/views/ocorrencias/create.blade.php
- resources/views/ocorrencias/show.blade.php

3.4 Notificações
Endpoints:
- GET /notificacoes (NotificacaoController@index)
- POST /notificacoes/{notificacao}/marcar-lida (NotificacaoController@marcarLida)
- GET /api/notificacoes/count (NotificacaoController@contarNaoLidas)

Comportamento:
- Ao abrir /notificacoes, as notificações não lidas do usuário são marcadas como lidas.
- A listagem é paginada e inclui a ocorrência e o aluno quando existirem.
- O badge no menu lateral atualiza automaticamente a cada 30 segundos usando /api/notificacoes/count.

View:
- resources/views/notificacoes/index.blade.php

4) Banco de Dados (Entidades e Relações)
4.1 Tabela: users
- Campo adicional: role (enum): aqv | portaria | professor

4.2 Tabela: alunos
- Campos principais:
  - nome
  - rm (único)
  - turma, curso
  - professor_id (nullable) -> FK users
  - responsavel, telefone_responsavel, email_responsavel (nullable)
  - ativo (boolean)
  - soft deletes

4.3 Tabela: ocorrencias
- Campos principais:
  - aluno_id -> FK alunos
  - aqv_id -> FK users (quem registrou)
  - tipo (enum): entrada_atrasada | saida_antecipada
  - motivo (text)
  - status (enum): pendente | aprovado | negado
  - data_ocorrencia, data_autorizacao (nullable)
  - confirmacao_portaria (nullable)
  - portaria_id (nullable) -> FK users (quem confirmou)
  - observacao (nullable)
  - soft deletes

4.4 Tabela: notificacoes
- Campos principais:
  - ocorrencia_id -> FK ocorrencias
  - usuario_id -> FK users (quem recebeu)
  - titulo, mensagem
  - lida (boolean)
  - lida_em (timestamp nullable)

5) Regras de Negócio (Detalhamento)
5.1 Registrar ocorrência (OcorrenciaController@store → OcorrenciaService@registrar)
- Apenas AQV.
- Cria ocorrência com:
  - aqv_id = Auth::id()
  - data_ocorrencia = now()
  - status = pendente
- Notifica o professor responsável do aluno via NotificacaoService.
- Se houver e-mail no professor e ele for “professor”, envia e-mail (OcorrenciaProfessorMail).

5.2 Aprovar ocorrência (OcorrenciaController@aprovar → OcorrenciaService@aprovar)
- Apenas AQV.
- Atualiza:
  - status = aprovado
  - data_autorizacao = now()
- Envia notificações para todos os usuários com role = portaria.

5.3 Negar ocorrência (OcorrenciaController@negar → OcorrenciaService@negar)
- Apenas AQV.
- Atualiza:
  - status = negado
  - data_autorizacao = now()

5.4 Confirmar portaria (OcorrenciaController@confirmarPortaria → OcorrenciaService@confirmarPortaria)
- Apenas Portaria.
- Atualiza:
  - confirmacao_portaria = now()
  - portaria_id = Auth::id()

5.5 Notificações (NotificacaoController)
- GET /notificacoes:
  - Busca por usuario_id = auth()->id()
  - Marca como lida as notificações não lidas ao acessar.
- POST /marcar-lida:
  - Garante que notificacao.usuario_id == auth()->id()
  - Atualiza lida/lida_em.
- GET /api/notificacoes/count:
  - Retorna JSON {count: <n>} para o badge do layout.

6) Componentes e UI
6.1 Layout principal (resources/views/layouts/app.blade.php)
- Estrutura em 2 colunas:
  - Sidebar fixa (w-64) com links e badge de notificações.
  - Área principal com topbar (data/hora) e mensagens flash.
- Badge “Notificações”:
  - Elemento #badge-notificacoes
  - Atualiza via JavaScript chamando /api/notificacoes/count a cada 30 segundos.

6.2 Views de detalhe
- Ocorrencias show:
  - Mostra aluno, motivo, observação e lista de notificações enviadas.
  - Mostra ações condicionais por role e status:
    - AQV: botões “Aprovar” / “Negar” quando status é pendente.
    - Portaria: botão “Confirmar portaria” quando status é aprovado e confirmacao_portaria ainda não foi preenchida.

7) Classes-Chave (responsabilidades)
- app/Http/Controllers/DashboardController.php
  - Monta dados e escolhe view conforme role.
- app/Http/Controllers/AlunoController.php
  - CRUD de alunos com filtro e estatísticas na tela de show.
- app/Http/Controllers/OcorrenciaController.php
  - Index com filtros (tipo, status, intervalo de datas, busca por nome/RM).
  - Create/store/show.
  - Aprovar/Negar/Confirmar portaia.
- app/Http/Controllers/NotificacaoController.php
  - Listagem, marcação como lida, e endpoint de contador.

- app/Services/OcorrenciaService.php
  - registrar(): cria ocorrência + notifica professor responsavel
  - aprovar(): atualiza status + notifica portarias
  - negar(): atualiza status
  - confirmarPortaria(): registra confirmação

- app/Services/NotificacaoService.php
  - enviar(): cria Notificacao e envia e-mail para professor quando aplicável

- app/Models
  - User: role helpers (isAqv/isPortaria/isProfessor)
  - Aluno: relacionamento com professorResponsavel e ocorrências + scope de busca
  - Ocorrencia: scopes (pendentes/aprovados/hoje) + helpers para labels e cores
  - Notificacao: marcarComoLida + escopo de não lidas

- app/Http/Middleware/CheckRole.php
  - Middleware para restringir rotas por role.

- app/Mail/OcorrenciaProfessorMail.php
  - Mailable para envio de e-mail (quando configurado e aplicável).

- app/Policies/OcorrenciaPolicy.php
  - Regras de autorização conceituais para ações (criar/atualizar/aprovar/confirmar).

8) Rotas Principais (Resumo)
- GET / → redireciona para login
- GET /dashboard → DashboardController@index
- Resource /alunos → AlunoController (CRUD)
- Resource /ocorrencias → OcorrenciaController (index/create/store/show)
- POST /ocorrencias/{ocorrencia}/aprovar → AQV
- POST /ocorrencias/{ocorrencia}/negar → AQV
- POST /ocorrencias/{ocorrencia}/confirmar-portaria → Portaria
- GET /notificacoes → NotificacaoController@index
- POST /notificacoes/{notificacao}/marcar-lida → NotificacaoController@marcarLida
- GET /api/notificacoes/count → NotificacaoController@contarNaoLidas

9) Como Rodar o Projeto (passo a passo)
Recomendações típicas para Laravel:
1. Instalar dependências:
   - composer install
   - npm install
2. Configurar arquivo de ambiente:
   - copiar .env.example para .env
3. Gerar chave:
   - php artisan key:generate
4. Configurar banco (no .env) e rodar migrations:
   - php artisan migrate --force
5. Build do front:
   - npm run build (ou npm run dev em modo desenvolvimento)
6. Rodar servidor:
   - php artisan serve

Comandos de teste (existem testes em /tests):
- php artisan test

10) Observações e Pontos de Atenção
- Middleware role:
  - No routes/web.php, actions usam middleware('role:aqv') e middleware('role:portaria').
  - O projeto inclui comentários sobre registrar alias em bootstrap/app.php (caso necessário para Laravel 11).
- E-mail:
  - Notificações podem disparar e-mail para professor, dependendo de configuração do Mail no .env.
- Soft deletes:
  - Alunos e Ocorrências usam SoftDeletes.
- Performance:
  - Index de ocorrências usa carregamento com relações (ex.: com aluno e aqv) e paginação.

FIM

