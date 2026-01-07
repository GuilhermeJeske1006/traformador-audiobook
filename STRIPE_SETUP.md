# Configuração do Stripe

Este documento explica como configurar o Stripe para aceitar pagamentos no sistema.

## 1. Criar Conta no Stripe

1. Acesse [https://stripe.com](https://stripe.com) e crie uma conta
2. Complete o processo de verificação da conta

## 2. Obter as Chaves da API

1. Acesse o Dashboard do Stripe
2. Vá em **Developers** → **API keys**
3. Copie:
   - **Publishable key** (começa com `pk_`)
   - **Secret key** (começa com `sk_`)

## 3. Criar um Produto e Preço

1. No Dashboard do Stripe, vá em **Products** → **Add Product**
2. Configure o produto:
   - **Name**: Plano Premium Transformador Audiobook
   - **Description**: Acesso ilimitado a todos os recursos
3. Configure o preço:
   - **Pricing model**: Standard pricing
   - **Price**: R$ 49,00
   - **Billing period**: Monthly
4. Clique em **Save product**
5. Copie o **Price ID** (começa com `price_`)

## 4. Configurar Webhook

1. No Dashboard do Stripe, vá em **Developers** → **Webhooks**
2. Clique em **Add endpoint**
3. Configure:
   - **Endpoint URL**: `https://seu-dominio.com/stripe/webhook`
   - **Events to send**: Selecione os seguintes eventos:
     - `customer.subscription.created`
     - `customer.subscription.updated`
     - `customer.subscription.deleted`
     - `invoice.payment_succeeded`
     - `invoice.payment_failed`
4. Clique em **Add endpoint**
5. Copie o **Signing secret** (começa com `whsec_`)

## 5. Configurar Variáveis de Ambiente

Adicione as seguintes variáveis no arquivo `.env`:

```env
STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
STRIPE_PRICE_ID=price_xxxxxxxxxxxxxxxxxxxxxxxx
CASHIER_CURRENCY=brl
CASHIER_CURRENCY_LOCALE=pt_BR
```

## 6. Executar as Migrations

Execute as migrations para criar as tabelas necessárias:

```bash
php artisan migrate
```

## 7. Testar a Integração

### Modo de Teste

No modo de teste, você pode usar os seguintes cartões:

- **Cartão com sucesso**: 4242 4242 4242 4242
- **Cartão que requer autenticação**: 4000 0025 0000 3155
- **Cartão que falha**: 4000 0000 0000 9995

**CVV**: Qualquer 3 dígitos
**Data de validade**: Qualquer data futura
**CEP**: Qualquer CEP

### Criar um Usuário Administrador

Para criar um usuário administrador que pode acessar sem pagamento:

```bash
php artisan tinker
```

```php
$user = User::find(1); // ou o ID do seu usuário
$user->is_admin = true;
$user->save();
```

## 8. Modo de Produção

Para usar em produção:

1. Ative sua conta Stripe completamente
2. Vá em **Developers** → **API keys**
3. Use as chaves de **produção** (começam com `pk_live_` e `sk_live_`)
4. Atualize o webhook com a URL de produção
5. Atualize as variáveis no `.env` de produção

## Funcionalidades Implementadas

### Para Usuários Regulares
- Acesso ao sistema apenas com assinatura ativa
- Página de criação de assinatura com Stripe Elements
- Página de gerenciamento de assinatura
- Cancelamento de assinatura (continua até o fim do período)
- Reativação de assinatura cancelada

### Para Administradores
- Acesso completo sem necessidade de pagamento
- Campo `is_admin` na tabela users

## Rotas Disponíveis

- `/subscription/create` - Criar nova assinatura
- `/subscription/manage` - Gerenciar assinatura existente
- `/app` - Aplicação principal (requer assinatura ou ser admin)

## Middleware

O middleware `subscribed` verifica:
1. Se o usuário é administrador → permite acesso
2. Se o usuário tem assinatura ativa → permite acesso
3. Caso contrário → redireciona para página de assinatura

## Webhooks

Os webhooks do Stripe são processados automaticamente pelo Laravel Cashier na rota `/stripe/webhook`.

Certifique-se de que esta rota está acessível publicamente para o Stripe enviar notificações.

## Suporte

Para mais informações sobre o Laravel Cashier:
- [Documentação Oficial](https://laravel.com/docs/billing)
- [Documentação do Stripe](https://stripe.com/docs)
