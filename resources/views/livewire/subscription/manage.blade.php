<div class="min-h-screen bg-gradient-to-b from-white to-zinc-50 dark:from-zinc-900 dark:to-zinc-950">
    <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Gerenciar Assinatura
            </h1>
            <p class="mt-4 text-lg text-zinc-600 dark:text-zinc-400">
                Gerencie sua assinatura e informações de pagamento
            </p>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ session('message') }}
            </div>
        @endif

        @if ($isSubscribed)
            <div class="rounded-3xl bg-white p-8 shadow-xl ring-1 ring-zinc-200 dark:bg-zinc-800 dark:ring-zinc-700">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">Plano Premium</h3>
                        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                            @if ($onGracePeriod)
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                    Cancelada - Ativa até {{ $subscription->ends_at->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                    Ativa
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-zinc-900 dark:text-white">R$ 49</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">/mês</p>
                    </div>
                </div>

                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6 mt-6">
                    <h4 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Recursos Incluídos</h4>
                    <ul class="space-y-3 text-zinc-600 dark:text-zinc-400">
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Conversões ilimitadas
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Sem limite de caracteres
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            20+ vozes premium
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Suporte prioritário
                        </li>
                    </ul>
                </div>

                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6 mt-6">
                    <div class="flex gap-4">
                        @if ($onGracePeriod)
                            <button
                                wire:click="resume"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-blue-500 transition-colors"
                            >
                                Reativar Assinatura
                            </button>
                        @else
                            <button
                                wire:click="cancel"
                                wire:confirm="Tem certeza que deseja cancelar sua assinatura?"
                                class="flex-1 rounded-lg bg-red-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-red-500 transition-colors"
                            >
                                Cancelar Assinatura
                            </button>
                        @endif
                    </div>
                </div>

                @if ($subscription)
                    <div class="mt-6 text-sm text-zinc-600 dark:text-zinc-400">
                        <p>Próxima cobrança: {{ $subscription->asStripeSubscription()->current_period_end ? date('d/m/Y', $subscription->asStripeSubscription()->current_period_end) : 'N/A' }}</p>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center rounded-3xl bg-white p-12 shadow-xl ring-1 ring-zinc-200 dark:bg-zinc-800 dark:ring-zinc-700">
                <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">Nenhuma assinatura ativa</h3>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                    Você não possui uma assinatura ativa no momento.
                </p>
                <a
                    href="{{ route('subscription.create') }}"
                    class="mt-6 inline-flex rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500 transition-colors"
                    wire:navigate
                >
                    Assinar Agora
                </a>
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('app') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">
                ← Voltar para o aplicativo
            </a>
        </div>
    </div>
</div>
