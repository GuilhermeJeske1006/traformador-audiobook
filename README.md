# Transformador de Audiobook

## Sobre o Projeto

O **Transformador de Audiobook** é uma aplicação Laravel que converte arquivos PDF em audiobooks automaticamente usando a API de Text-to-Speech do Google Cloud. A aplicação permite que você faça upload de PDFs e receba áudios em MP3 com narração em português brasileiro.

## Funcionalidades

- Upload de arquivos PDF
- Extração automática de texto dos PDFs
- Conversão de texto em áudio usando Google Cloud Text-to-Speech
- Processamento assíncrono com filas RabbitMQ
- Interface web para gerenciamento de audiobooks
- Acompanhamento em tempo real do progresso de conversão
- Gerenciamento de autenticação de usuários
- Sistema de assinaturas com Stripe
- Personalização de audiobooks (conversão para vídeo)

## Tecnologias Utilizadas

- **Backend**: Laravel 12
- **Frontend**: Livewire 3 + Flux UI
- **Banco de Dados**: MySQL
- **Filas**: RabbitMQ
- **Cache**: Redis
- **Storage**: MinIO (S3-compatible)
- **Extração de PDF**: Smalot PDF Parser
- **Text-to-Speech**: Google Cloud Text-to-Speech API
- **Autenticação**: Laravel Fortify
- **Pagamentos**: Laravel Cashier + Stripe

## Requisitos do Sistema

- PHP 8.2 ou superior
- Composer
- Node.js e NPM
- Docker e Docker Compose (para MySQL, RabbitMQ e MinIO)
- Redis
- Conta Google Cloud Platform com Text-to-Speech API habilitada
- Conta Stripe (para sistema de pagamentos)
- Stripe CLI (para testar webhooks localmente)

## Instalação

### 1. Clone o Repositório

```bash
git clone <url-do-repositorio>
cd traformador-audiobook
```

### 2. Inicie os Serviços Docker

Inicie os containers do MySQL, RabbitMQ e MinIO:

```bash
docker-compose up -d
```

Verifique se os serviços estão rodando:

```bash
docker-compose ps
```

### 3. Instale as Dependências

Utilize o comando de setup automático:

```bash
composer run setup
```

Este comando irá:
- Instalar dependências do Composer
- Criar o arquivo `.env` se não existir
- Gerar a chave da aplicação
- Executar as migrations
- Instalar dependências do NPM
- Compilar os assets

Ou instale manualmente:

```bash
composer install
npm install
```

### 5. Configure as Variáveis de Ambiente

Edite o arquivo `.env` e configure as seguintes variáveis:

#### Banco de Dados (MySQL via Docker)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=traformador_audiobook
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

#### RabbitMQ (via Docker)
```env
RABBITMQ_HOST=127.0.0.1
RABBITMQ_PORT=5672
RABBITMQ_VHOST=/
RABBITMQ_LOGIN=guest
RABBITMQ_PASSWORD=guest
RABBITMQ_QUEUE=audiobooks
QUEUE_CONNECTION=rabbitmq
```

#### Redis
```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
CACHE_STORE=redis
```

#### MinIO (Storage S3-compatible via Docker)
```env
FILESYSTEM_DISK=minio
MINIO_ACCESS_KEY=minioadmin
MINIO_SECRET_KEY=minioadmin
MINIO_REGION=us-east-1
MINIO_BUCKET=audiobooks
MINIO_ENDPOINT=http://localhost:9100

AWS_ACCESS_KEY_ID=minioadmin
AWS_SECRET_ACCESS_KEY=minioadmin
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=audiobooks
AWS_ENDPOINT=http://localhost:9100
AWS_USE_PATH_STYLE_ENDPOINT=true
```

### 6. Configure o Google Cloud Text-to-Speech

1. Acesse o [Google Cloud Console](https://console.cloud.google.com/)
2. Crie um novo projeto ou selecione um existente
3. Habilite a API Text-to-Speech
4. Crie uma Service Account e baixe o arquivo JSON de credenciais
5. Coloque o arquivo JSON na raiz do projeto
6. Configure o caminho no `.env`:

```env
GOOGLE_APPLICATION_CREDENTIALS=/caminho/para/seu/arquivo-credenciais.json
```

### 7. Configure o Stripe

1. Crie uma conta no [Stripe](https://stripe.com)
2. Acesse o Dashboard e pegue suas chaves de teste
3. Crie um produto e um preço (plano de assinatura)
4. Configure no `.env`:

```env
STRIPE_KEY=pk_test_sua_chave_publica
STRIPE_SECRET=sk_test_sua_chave_secreta
STRIPE_WEBHOOK_SECRET=whsec_seu_webhook_secret
STRIPE_PRICE_ID=price_seu_id_de_preco
CASHIER_CURRENCY=brl
```

**Importante**: O `STRIPE_WEBHOOK_SECRET` será gerado quando você iniciar o Stripe CLI (veja a seção "Testando Webhooks do Stripe" abaixo).

## Como Usar

### Iniciando a Aplicação

#### Modo Desenvolvimento (Recomendado)

Execute todos os serviços necessários de uma vez:

```bash
composer run dev
```

Este comando iniciará automaticamente:
- Servidor web (porta 8000)
- Worker de filas
- Logs em tempo real
- Vite para hot reload dos assets

#### Modo Manual

Se preferir iniciar cada serviço separadamente:

1. **Inicie o servidor web**:
```bash
php artisan serve
```

2. **Inicie o worker de filas** (em outro terminal):
```bash
php artisan queue:listen --tries=1
```

3. **Inicie o Vite** (em outro terminal):
```bash
npm run dev
```

4. **Monitore os logs** (opcional, em outro terminal):
```bash
php artisan pail --timeout=0
```

### Testando Webhooks do Stripe

Para testar webhooks do Stripe em desenvolvimento local:

1. **Instale o Stripe CLI** (se ainda não tiver):
   - macOS: `brew install stripe/stripe-cli/stripe`
   - Outras plataformas: [Documentação oficial](https://stripe.com/docs/stripe-cli)

2. **Faça login no Stripe CLI**:
```bash
stripe login
```

3. **Inicie o listener de webhooks**:
```bash
stripe listen --forward-to localhost:8000/stripe/webhook
```

4. **Copie o webhook secret** exibido no terminal (começa com `whsec_...`)

5. **Atualize o `.env`** com o novo webhook secret:
```env
STRIPE_WEBHOOK_SECRET=whsec_seu_novo_secret
```

6. **Limpe o cache de configuração**:
```bash
php artisan config:clear
```

Agora os webhooks do Stripe serão encaminhados para sua aplicação local!

**Testando pagamentos**: Use os [cartões de teste do Stripe](https://stripe.com/docs/testing):
- Sucesso: `4242 4242 4242 4242`
- CVV: qualquer 3 dígitos
- Data: qualquer data futura

### Acessando a Aplicação

1. Abra o navegador em `http://localhost:8000`
2. Crie uma conta ou faça login
3. Assine um plano (modo teste do Stripe)
4. Acesse a página de upload de audiobooks
5. Selecione um arquivo PDF e faça o upload
6. Aguarde o processamento (você pode acompanhar o progresso na interface)
7. Quando concluído, faça o download do audiobook em MP3
8. Opcionalmente, personalize o audiobook convertendo para vídeo

## Arquitetura da Aplicação

### Fluxo de Processamento

```
1. Upload do PDF
   ↓
2. Job enfileirado no RabbitMQ (ProcessAudiobookJob)
   ↓
3. Extração de texto do PDF (PdfTextExtractorService)
   ↓
4. Divisão do texto em chunks de 4500 caracteres
   ↓
5. Conversão de cada chunk em áudio (TextToSpeechService)
   ↓
6. Mesclagem dos áudios em um único arquivo MP3
   ↓
7. Upload para MinIO (storage S3-compatible)
   ↓
8. Disponibilização do arquivo para download
```

### Fluxo de Assinatura

```
1. Usuário clica em "Assinar"
   ↓
2. Redirecionado para Checkout do Stripe
   ↓
3. Stripe processa o pagamento
   ↓
4. Webhook enviado para /stripe/webhook
   ↓
5. Laravel Cashier atualiza status da assinatura
   ↓
6. Usuário ganha acesso aos recursos premium
```

### Estrutura de Diretórios Principais

```
app/
├── Http/
│   └── Controllers/       # Controllers da aplicação
├── Jobs/
│   ├── ProcessAudiobookJob.php   # Job de processamento de áudio
│   └── ProcessVideoJob.php       # Job de conversão para vídeo
├── Livewire/
│   ├── AudiobookPage.php         # Página principal de audiobooks
│   ├── AudiobookCustomization.php # Personalização/conversão vídeo
│   └── Subscription/             # Componentes de assinatura
├── Models/
│   ├── Audiobook.php      # Model do audiobook
│   └── User.php           # Model do usuário (com traits do Cashier)
└── Services/
    ├── PdfTextExtractorService.php  # Extração de PDF
    └── TextToSpeechService.php      # Conversão TTS

config/
└── cashier.php            # Configurações do Laravel Cashier

database/
└── migrations/            # Migrations do banco

docker-compose.yml         # Serviços Docker (MySQL, RabbitMQ, MinIO)
```

### Principais Componentes

#### ProcessAudiobookJob
- **Localização**: `app/Jobs/ProcessAudiobookJob.php`
- **Função**: Job responsável por coordenar todo o processo de conversão
- **Timeout**: 3600 segundos (1 hora)
- **Memória**: 512MB

#### PdfTextExtractorService
- **Localização**: `app/Services/PdfTextExtractorService.php`
- **Função**: Extrai texto de arquivos PDF usando Smalot PDF Parser
- **Retorno**: String com todo o texto extraído

#### TextToSpeechService
- **Localização**: `app/Services/TextToSpeechService.php`
- **Função**: Converte texto em áudio usando Google Cloud TTS
- **Otimizações**:
  - Divisão automática em chunks de 4500 caracteres
  - Streaming direto para arquivo (evita consumo excessivo de memória)
  - Garbage collection após cada chunk

#### Audiobook Model
- **Localização**: `app/Models/Audiobook.php`
- **Campos**:
  - `title`: Título do audiobook
  - `original_filename`: Nome original do PDF
  - `pdf_path`: Caminho do PDF no storage
  - `audio_path`: Caminho do áudio gerado
  - `extracted_text`: Texto extraído (longText - até 4GB)
  - `status`: pending, processing, completed, failed
  - `total_characters`: Total de caracteres do texto
  - `processing_progress`: Progresso em porcentagem (0-100)
  - `error_message`: Mensagem de erro caso falhe

## Configurações Importantes

### Limites de Memória

O job de processamento está configurado com 512MB de memória para lidar com PDFs grandes:

```php
// app/Jobs/ProcessAudiobookJob.php
ini_set('memory_limit', '512M');
```

### Timeout do Worker

Para PDFs muito grandes, certifique-se de configurar um timeout adequado:

```bash
php artisan queue:listen --timeout=3600
```

### Tamanho Máximo de Upload

Configure no `php.ini`:

```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
```

## Monitoramento e Logs

### Ver Logs em Tempo Real

```bash
php artisan pail
```

### Verificar Status da Fila

```bash
php artisan queue:monitor rabbitmq
```

### Acessar Interface do RabbitMQ

Acesse `http://localhost:15672` com as credenciais:
- Usuário: `guest`
- Senha: `guest`

### Reiniciar Workers

Após alterações no código:

```bash
php artisan queue:restart
```

### Limpar Jobs Falhados

```bash
php artisan queue:flush
```

### Ver Jobs Falhados

```bash
php artisan queue:failed
```

## Troubleshooting

### Erro: "Memory exhausted"

**Solução**: Aumente o limite de memória no job ou no php.ini

```php
ini_set('memory_limit', '1G');
```

### Erro: "SQLSTATE[22001]: String data, right truncated"

**Solução**: Execute novamente as migrations para atualizar a coluna `extracted_text` para `longText`:

```bash
php artisan migrate:fresh
```

### Redis não conecta

**Solução**: Verifique se o Redis está rodando:

```bash
redis-cli ping
# Deve retornar: PONG
```

### Containers Docker não iniciam

**Solução**: Verifique o status e os logs dos containers:

```bash
docker-compose ps
docker-compose logs mysql
docker-compose logs rabbitmq
docker-compose logs minio
```

### RabbitMQ não conecta

**Solução**: Verifique se o container está rodando:

```bash
docker-compose ps rabbitmq
```

Se necessário, reinicie:

```bash
docker-compose restart rabbitmq
```

### MinIO não está acessível

**Solução**: Acesse o console do MinIO em `http://localhost:9101` com as credenciais do `.env` e verifique se o bucket foi criado.

### Stripe webhook não recebe eventos

**Solução**: Verifique se:
1. O Stripe CLI está rodando: `stripe listen --forward-to localhost:8000/stripe/webhook`
2. O servidor Laravel está rodando na porta 8000
3. O `STRIPE_WEBHOOK_SECRET` no `.env` corresponde ao exibido no Stripe CLI
4. Você executou `php artisan config:clear` após atualizar o `.env`

### Google Cloud API retorna erro

**Solução**: Verifique se:
1. A API Text-to-Speech está habilitada no projeto
2. O arquivo de credenciais está no caminho correto
3. A Service Account tem as permissões necessárias

## Segurança

- Credenciais do Google Cloud são armazenadas fora do repositório
- Arquivos `.env` nunca devem ser commitados
- Autenticação de usuários via Laravel Fortify
- Validação de tipos de arquivo no upload
- Proteção contra CSRF habilitada
- Webhooks do Stripe validados com assinatura secreta
- Comunicação segura com Stripe via HTTPS
- Dados sensíveis de pagamento nunca tocam o servidor (processados pelo Stripe)

## Performance

### Otimizações Implementadas

1. **Processamento Assíncrono**: Filas RabbitMQ para não bloquear requisições
2. **Storage Distribuído**: MinIO (S3-compatible) para escalabilidade
3. **Cache Redis**: Reduz carga no banco de dados
4. **Streaming de Áudio**: Escrita direta em arquivo evita acúmulo em memória
5. **Garbage Collection**: Limpeza explícita de memória após cada chunk
6. **Chunking Inteligente**: Divisão respeitando limites da API do Google
7. **Índices no Banco**: Otimização de queries
8. **Docker Compose**: Serviços isolados e escaláveis

### Capacidade

- **Tamanho médio de PDF**: Até 100 páginas sem problemas
- **PDFs grandes**: Testado com até 500 páginas
- **Concurrent jobs**: Configurável via número de workers
- **Storage**: Ilimitado com MinIO (limitado apenas pelo disco)

## Contribuindo

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a licença MIT.

## Suporte

Para reportar bugs ou solicitar features, abra uma issue no repositório.

## Autor

Desenvolvido para converter PDFs em audiobooks de forma automatizada e eficiente.
