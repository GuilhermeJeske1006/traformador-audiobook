#!/bin/bash

# Script para iniciar a aplicação completa
set -e  # Para o script se algum comando falhar

echo "🚀 Iniciando a aplicação Traformador Audiobook..."
echo ""

# Cores para output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Verificar se o arquivo .env existe
echo -e "${BLUE}[1/8]${NC} Verificando arquivo .env..."
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  Arquivo .env não encontrado. Copiando de .env.example...${NC}"
    cp .env.example .env
    echo -e "${YELLOW}⚠️  Por favor, configure as variáveis de ambiente no arquivo .env${NC}"
    exit 1
fi
echo -e "${GREEN}✓${NC} Arquivo .env encontrado"
echo ""

# 2. Parar containers antigos
echo -e "${BLUE}[2/8]${NC} Parando containers Docker antigos..."
docker-compose down 2>/dev/null || true
echo -e "${GREEN}✓${NC} Containers antigos parados"
echo ""

# 3. Iniciar containers Docker
echo -e "${BLUE}[3/8]${NC} Iniciando containers Docker (MinIO, MySQL, RabbitMQ)..."
docker-compose up -d
echo -e "${GREEN}✓${NC} Containers Docker iniciados"
echo ""

# 4. Aguardar os serviços ficarem prontos
echo -e "${BLUE}[4/8]${NC} Aguardando serviços ficarem prontos..."
echo "   Aguardando MySQL..."
until docker exec mysql mysqladmin ping -h localhost -u root -psenha123 --silent 2>/dev/null; do
    sleep 1
done
echo -e "${GREEN}✓${NC} MySQL pronto"
echo ""

# 5. Instalar dependências do Composer (se necessário)
echo -e "${BLUE}[5/8]${NC} Verificando dependências do Composer..."
if [ ! -d "vendor" ]; then
    echo "   Instalando dependências do Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
else
    echo -e "${GREEN}✓${NC} Dependências do Composer já instaladas"
fi
echo ""

# 6. Instalar dependências do NPM (se necessário)
echo -e "${BLUE}[6/8]${NC} Verificando dependências do NPM..."
if [ ! -d "node_modules" ]; then
    echo "   Instalando dependências do NPM..."
    npm install
else
    echo -e "${GREEN}✓${NC} Dependências do NPM já instaladas"
fi
echo ""

# 7. Executar migrations
echo -e "${BLUE}[7/8]${NC} Executando migrations..."
php artisan migrate --force
echo -e "${GREEN}✓${NC} Migrations executadas"
echo ""

# 8. Iniciar servidores de desenvolvimento
echo -e "${BLUE}[8/8]${NC} Iniciando servidores de desenvolvimento..."
echo ""

# Função para limpar processos ao sair
cleanup() {
    echo ""
    echo -e "${YELLOW}🛑 Parando servidores...${NC}"
    kill $VITE_PID 2>/dev/null || true
    kill $ARTISAN_PID 2>/dev/null || true
    kill $QUEUE_PID 2>/dev/null || true
    exit 0
}

trap cleanup SIGINT SIGTERM

# Iniciar Vite em background
echo -e "${GREEN}▶${NC}  Iniciando Vite (npm run dev)..."
npm run dev &
VITE_PID=$!

# Aguardar um pouco para o Vite iniciar
sleep 2

# Iniciar Queue Worker
echo -e "${GREEN}▶${NC}  Iniciando Queue Worker (php artisan queue:work rabbitmq)..."
php artisan queue:work rabbitmq --sleep=3 --tries=3 &
QUEUE_PID=$!

# Aguardar um pouco para o Queue iniciar
sleep 1

# Iniciar Laravel
echo -e "${GREEN}▶${NC}  Iniciando Laravel (php artisan serve)..."
php artisan serve &
ARTISAN_PID=$!

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✓ Aplicação iniciada com sucesso!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "📱 Aplicação Laravel: ${BLUE}http://127.0.0.1:8000${NC}"
echo -e "⚡ Vite Dev Server:    ${BLUE}http://127.0.0.1:5173${NC}"
echo -e "🔄 Queue Worker:      ${GREEN}Rodando (RabbitMQ)${NC}"
echo -e "🗄️  MinIO Console:     ${BLUE}http://127.0.0.1:9101${NC}"
echo -e "🐰 RabbitMQ Console:  ${BLUE}http://127.0.0.1:15672${NC}"
echo -e "🗃️  MySQL:             ${BLUE}127.0.0.1:3306${NC}"
echo ""
echo -e "${YELLOW}Pressione Ctrl+C para parar todos os serviços${NC}"
echo ""

# Manter o script rodando
wait
