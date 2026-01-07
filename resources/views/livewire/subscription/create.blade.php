<div class="min-h-screen bg-gradient-to-b from-white to-zinc-50 dark:from-zinc-900 dark:to-zinc-950">
    <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Assine para Continuar
            </h1>
            <p class="mt-4 text-lg text-zinc-600 dark:text-zinc-400">
                Escolha um plano para acessar todos os recursos do Transformador Audiobook
            </p>
        </div>

        <div class="rounded-3xl bg-gradient-to-br from-blue-600 to-violet-600 p-8 shadow-xl">
            <div class="text-white">
                <h3 class="text-2xl font-bold">Plano Premium</h3>
                <p class="mt-4 text-blue-100">
                    Acesso completo a todos os recursos
                </p>
                <div class="mt-6 flex items-baseline gap-x-1">
                    <span class="text-5xl font-bold">R$ 49</span>
                    <span class="text-xl text-blue-100">/mês</span>
                </div>

                <ul class="mt-8 space-y-3 text-blue-100">
                    <li class="flex gap-x-3">
                        <svg class="h-6 w-5 flex-none text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                        Conversões ilimitadas
                    </li>
                    <li class="flex gap-x-3">
                        <svg class="h-6 w-5 flex-none text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                        Sem limite de caracteres
                    </li>
                    <li class="flex gap-x-3">
                        <svg class="h-6 w-5 flex-none text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                        20+ vozes premium
                    </li>
                    <li class="flex gap-x-3">
                        <svg class="h-6 w-5 flex-none text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                        Processamento prioritário
                    </li>
                    <li class="flex gap-x-3">
                        <svg class="h-6 w-5 flex-none text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                        Suporte prioritário
                    </li>
                </ul>

                <div class="mt-10">
                    <div id="card-element" class="p-4 bg-white rounded-lg">
                        <!-- Stripe Card Element will be inserted here -->
                    </div>
                    <div id="card-errors" class="mt-2 text-sm text-red-100"></div>
                </div>

                <button
                    wire:click="subscribe"
                    id="card-button"
                    class="mt-6 w-full rounded-lg bg-white px-6 py-4 text-center text-lg font-semibold text-blue-600 hover:bg-blue-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Assinar Agora
                </button>

                <p class="mt-4 text-center text-sm text-blue-100">
                    Você pode cancelar a qualquer momento
                </p>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">
                ← Voltar para a página inicial
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('cashier.key') }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#1f2937',
                '::placeholder': {
                    color: '#9ca3af',
                },
            },
        },
    });

    cardElement.mount('#card-element');

    cardElement.on('change', (event) => {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    const cardButton = document.getElementById('card-button');
    cardButton.addEventListener('click', async () => {
        cardButton.disabled = true;
        cardButton.textContent = 'Processando...';

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            const displayError = document.getElementById('card-errors');
            displayError.textContent = error.message;
            cardButton.disabled = false;
            cardButton.textContent = 'Assinar Agora';
        } else {
            @this.set('paymentMethod', paymentMethod.id);
            @this.call('subscribe');
        }
    });
</script>
@endpush
