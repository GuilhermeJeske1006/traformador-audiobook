# Transformador de Audiobook

## Sobre o Projeto

O **Transformador de Audiobook** é uma aplicação Laravel que converte arquivos PDF em audiobooks automaticamente usando a API de Text-to-Speech do Google Cloud. A aplicação permite que você faça upload de PDFs e receba áudios em MP3 com narração em português brasileiro.

## Funcionalidades

- Upload de arquivos PDF
- Extração automática de texto dos PDFs
- Conversão de texto em áudio usando Google Cloud Text-to-Speech
- Processamento assíncrono com filas Redis
- Interface web para gerenciamento de audiobooks
- Acompanhamento em tempo real do progresso de conversão
- Gerenciamento de autenticação de usuários

## Tecnologias Utilizadas

- **Backend**: Laravel 12
- **Frontend**: Livewire 3 + Flux UI
- **Banco de Dados**: MySQL
- **Filas**: Redis
- **Extração de PDF**: Smalot PDF Parser
- **Text-to-Speech**: Google Cloud Text-to-Speech API
- **Autenticação**: Laravel Fortify

## Requisitos do Sistema

- PHP 8.2 ou superior
- Composer
- Node.js e NPM
- MySQL 5.7 ou superior
- Redis
- Conta Google Cloud Platform com Text-to-Speech API habilitada

## Instalação

### 1. Clone o Repositório

```bash
git clone <url-do-repositorio>
cd traformador-audiobook
```

### 2. Instale as Dependências

```bash
composer install
npm install
```

### 3. Configure o Ambiente

Copie o arquivo de exemplo de configuração:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

### 4. Configure o Banco de Dados

Edite o arquivo `.env` e configure suas credenciais do MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=traformador_audiobook
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

Crie o banco de dados:

```bash
mysql -u root -p -e "CREATE DATABASE traformador_audiobook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Execute as migrations:

```bash
php artisan migrate
```

### 5. Configure o Redis

Certifique-se de que o Redis está instalado e rodando. No arquivo `.env`:

```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
QUEUE_CONNECTION=redis
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

### 7. Configure o Storage

Crie o link simbólico para o storage público:

```bash
php artisan storage:link
```

### 8. Compile os Assets

```bash
npm run build
```

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
php artisan queue:work redis --tries=3 --timeout=3600
```

3. **Inicie o Vite** (em outro terminal):
```bash
npm run dev
```

### Acessando a Aplicação

1. Abra o navegador em `http://localhost:8000`
2. Crie uma conta ou faça login
3. Acesse a página de upload de audiobooks
4. Selecione um arquivo PDF e faça o upload
5. Aguarde o processamento (você pode acompanhar o progresso na interface)
6. Quando concluído, faça o download do audiobook em MP3

## Arquitetura da Aplicação

### Fluxo de Processamento

```
1. Upload do PDF
   ↓
2. Job enfileirado no Redis (ProcessAudiobookJob)
   ↓
3. Extração de texto do PDF (PdfTextExtractorService)
   ↓
4. Divisão do texto em chunks de 4500 caracteres
   ↓
5. Conversão de cada chunk em áudio (TextToSpeechService)
   ↓
6. Mesclagem dos áudios em um único arquivo MP3
   ↓
7. Armazenamento e disponibilização do arquivo
```

### Estrutura de Diretórios Principais

```
app/
├── Http/
│   └── Controllers/       # Controllers da aplicação
├── Jobs/
│   └── ProcessAudiobookJob.php  # Job de processamento
├── Models/
│   └── Audiobook.php      # Model do audiobook
└── Services/
    ├── PdfTextExtractorService.php  # Extração de PDF
    └── TextToSpeechService.php      # Conversão TTS

database/
└── migrations/            # Migrations do banco

resources/
└── views/                # Views Livewire

storage/
└── app/
    └── public/
        ├── pdfs/         # PDFs enviados
        └── audiobooks/   # Áudios gerados
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
php artisan queue:work redis --timeout=3600
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
php artisan queue:monitor redis
```

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

## Performance

### Otimizações Implementadas

1. **Processamento Assíncrono**: Filas Redis para não bloquear requisições
2. **Streaming de Áudio**: Escrita direta em arquivo evita acúmulo em memória
3. **Garbage Collection**: Limpeza explícita de memória após cada chunk
4. **Chunking Inteligente**: Divisão respeitando limites da API do Google
5. **Índices no Banco**: Otimização de queries

### Capacidade

- **Tamanho médio de PDF**: Até 100 páginas sem problemas
- **PDFs grandes**: Testado com até 500 páginas
- **Concurrent jobs**: Configurável via número de workers

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
