# Transformador de PDF para Audiobook

Este projeto Laravel com Livewire converte arquivos PDF em audiobooks usando Google Cloud Text-to-Speech.

## Funcionalidades

- Upload de arquivos PDF
- Extração automática de texto do PDF
- Conversão de texto para áudio usando Google Cloud TTS
- Interface interativa com Livewire/Flux
- Processamento assíncrono com filas
- Player de áudio integrado
- Download dos audiobooks gerados
- Sistema de busca e gerenciamento de audiobooks

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e NPM
- Conta Google Cloud com Text-to-Speech API habilitada
- SQLite (ou outro banco de dados suportado pelo Laravel)

## Instalação

### 1. Clone o repositório e instale as dependências

```bash
composer install
npm install
```

### 2. Configure o arquivo .env

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure o Google Cloud Text-to-Speech

#### 3.1. Crie um projeto no Google Cloud Console
- Acesse: https://console.cloud.google.com
- Crie um novo projeto ou selecione um existente

#### 3.2. Habilite a API Text-to-Speech
- No menu, vá em "APIs & Services" > "Library"
- Busque por "Cloud Text-to-Speech API"
- Clique em "Enable"

#### 3.3. Crie uma Service Account
- Vá em "APIs & Services" > "Credentials"
- Clique em "Create Credentials" > "Service Account"
- Preencha os dados e clique em "Create"
- Na etapa de permissões, adicione o papel "Cloud Text-to-Speech User"
- Clique em "Done"

#### 3.4. Gere a chave JSON
- Na lista de Service Accounts, clique na conta que você criou
- Vá na aba "Keys"
- Clique em "Add Key" > "Create new key"
- Escolha o formato JSON e clique em "Create"
- O arquivo será baixado automaticamente

#### 3.5. Configure a credencial no projeto
- Mova o arquivo JSON baixado para uma pasta segura no projeto (ex: `storage/credentials/`)
- No arquivo `.env`, adicione o caminho completo para o arquivo:

```env
GOOGLE_APPLICATION_CREDENTIALS=/caminho/completo/para/arquivo-credentials.json
```

**Importante:** Adicione a pasta de credenciais ao `.gitignore` para não commitar dados sensíveis!

```bash
echo "storage/credentials/" >> .gitignore
```

### 4. Execute as migrations

```bash
php artisan migrate
```

### 5. Crie o link simbólico do storage

```bash
php artisan storage:link
```

### 6. Compile os assets

```bash
npm run build
# ou para desenvolvimento:
npm run dev
```

## Uso

### Iniciar o servidor de desenvolvimento

```bash
# Terminal 1 - Servidor Laravel
php artisan serve

# Terminal 2 - Queue Worker (para processar os audiobooks)
php artisan queue:work

# Terminal 3 - Vite (se estiver desenvolvendo)
npm run dev
```

Ou use o comando simplificado:

```bash
composer dev
```

### Usar a aplicação

1. Acesse `http://localhost:8000` no navegador
2. Preencha o título do audiobook
3. Selecione um arquivo PDF (máximo 50MB)
4. Clique em "Enviar e Processar"
5. O processamento acontecerá em background
6. Quando concluído, o player de áudio aparecerá na lista
7. Você pode ouvir online ou baixar o arquivo MP3

## Estrutura do Projeto

```
app/
├── Jobs/
│   └── ProcessAudiobookJob.php      # Job assíncrono para processar PDFs
├── Livewire/
│   ├── AudiobookList.php             # Componente de listagem
│   ├── AudiobookPage.php             # Página principal
│   └── AudiobookUpload.php           # Componente de upload
├── Models/
│   └── Audiobook.php                 # Model do audiobook
└── Services/
    ├── PdfTextExtractorService.php   # Extração de texto do PDF
    └── TextToSpeechService.php       # Conversão para áudio

database/
└── migrations/
    └── 2026_01_06_142113_create_audiobooks_table.php

resources/
└── views/
    └── livewire/
        ├── audiobook-list.blade.php
        ├── audiobook-page.blade.php
        └── audiobook-upload.blade.php
```

## Status de Processamento

Os audiobooks podem ter os seguintes status:

- **Pendente**: Upload concluído, aguardando processamento
- **Processando**: Extração de texto e conversão em andamento
- **Concluído**: Audiobook pronto para ser ouvido/baixado
- **Falhou**: Erro durante o processamento

## Limitações

- PDFs muito grandes podem demorar mais para processar
- O Google Cloud TTS tem custos por caractere processado (consulte a [tabela de preços](https://cloud.google.com/text-to-speech/pricing))
- Limite de upload configurado para 50MB
- A qualidade do áudio depende da qualidade do texto extraído do PDF

## Custos do Google Cloud TTS

- Primeiros 4 milhões de caracteres por mês: GRÁTIS
- Após isso: $4.00 por 1 milhão de caracteres (vozes padrão)
- Vozes WaveNet/Neural2: $16.00 por 1 milhão de caracteres

## Troubleshooting

### Erro de autenticação do Google Cloud

Verifique se:
- O caminho do arquivo de credenciais está correto no `.env`
- A API Text-to-Speech está habilitada no projeto
- A Service Account tem as permissões corretas

### Jobs não estão sendo processados

Certifique-se de que o queue worker está rodando:
```bash
php artisan queue:work
```

### Áudio não está sendo reproduzido

Verifique se:
- O link simbólico do storage foi criado: `php artisan storage:link`
- Os arquivos estão sendo salvos em `storage/app/public/audiobooks/`
- As permissões das pastas estão corretas

## Tecnologias Utilizadas

- Laravel 12
- Livewire 3
- Flux UI Components
- Google Cloud Text-to-Speech
- Smalot PDF Parser
- Tailwind CSS

## Licença

MIT
