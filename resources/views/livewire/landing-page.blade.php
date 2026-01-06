<div>
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-white to-zinc-50 dark:from-zinc-900 dark:to-zinc-950">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-28 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-6xl">
                    Transforme seus <span class="bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-transparent">Livros</span> em Audiobooks
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-zinc-600 dark:text-zinc-400">
                    Converta qualquer texto em áudio de alta qualidade com nossa tecnologia avançada de Text-to-Speech.
                    Simples, rápido e com vozes naturais.
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    @auth
                        <a href="{{ route('app') }}"
                           class="rounded-lg bg-zinc-900 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 transition-colors"
                           wire:navigate>
                            Acessar Aplicação
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="rounded-lg bg-zinc-900 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 transition-colors"
                           wire:navigate>
                            Começar Grátis
                        </a>
                    @endauth
                    <a href="#how-it-works" class="text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                        Saiba mais <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

            <!-- Hero Image/Demo -->
            <div class="mt-16 flow-root sm:mt-24">
                <div class="relative -m-2 rounded-xl bg-zinc-900/5 p-2 ring-1 ring-inset ring-zinc-900/10 dark:bg-zinc-100/5 dark:ring-zinc-100/10 lg:-m-4 lg:rounded-2xl lg:p-4">
                    <div class="aspect-video overflow-hidden rounded-md bg-white shadow-2xl ring-1 ring-zinc-900/10 dark:bg-zinc-800">
                        <div class="flex h-full items-center justify-center p-8">
                            <div class="text-center">
                               <img src="{{ './images/preview.png' }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-white py-24 dark:bg-zinc-900 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-4xl">
                    Recursos Poderosos
                </h2>
                <p class="mt-6 text-lg leading-8 text-zinc-600 dark:text-zinc-400">
                    Tudo o que você precisa para criar audiobooks de qualidade profissional.
                </p>
            </div>

            <div class="mx-auto mt-16 max-w-7xl sm:mt-20 lg:mt-24">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-3 lg:gap-y-16">
                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                                </svg>
                            </div>
                            Vozes Naturais
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-zinc-600 dark:text-zinc-400">
                            Tecnologia Text-to-Speech de última geração com vozes que soam naturais e expressivas.
                        </dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                            </div>
                            Processamento Rápido
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-zinc-600 dark:text-zinc-400">
                            Converta seus textos em áudio em minutos, não em horas. Sistema otimizado para velocidade.
                        </dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            Múltiplos Formatos
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-zinc-600 dark:text-zinc-400">
                            Suporte para diversos formatos de entrada e saída em MP3 de alta qualidade.
                        </dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                                </svg>
                            </div>
                            Personalização
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-zinc-600 dark:text-zinc-400">
                            Ajuste velocidade, tom e escolha entre diferentes vozes para criar o audiobook perfeito.
                        </dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </div>
                            Download Instantâneo
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-zinc-600 dark:text-zinc-400">
                            Baixe seus audiobooks imediatamente após o processamento, sem complicações.
                        </dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>
                            </div>
                            Múltiplos Idiomas
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-zinc-600 dark:text-zinc-400">
                            Suporte para diversos idiomas com vozes nativas e pronúncia precisa.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="bg-zinc-50 py-24 dark:bg-zinc-950 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-4xl">
                    Como Funciona
                </h2>
                <p class="mt-6 text-lg leading-8 text-zinc-600 dark:text-zinc-400">
                    Transforme texto em áudio em 3 passos simples
                </p>
            </div>

            <div class="mx-auto mt-16 max-w-5xl">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="relative text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-600 text-2xl font-bold text-white">
                            1
                        </div>
                        <h3 class="mt-6 text-lg font-semibold text-zinc-900 dark:text-white">
                            Upload do Texto
                        </h3>
                        <p class="mt-2 text-base text-zinc-600 dark:text-zinc-400">
                            Faça upload do seu arquivo de texto ou PDF. Nosso sistema aceita diversos formatos.
                        </p>
                    </div>

                    <div class="relative text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-600 text-2xl font-bold text-white">
                            2
                        </div>
                        <h3 class="mt-6 text-lg font-semibold text-zinc-900 dark:text-white">
                            Processamento
                        </h3>
                        <p class="mt-2 text-base text-zinc-600 dark:text-zinc-400">
                            Nossa IA converte o texto em áudio de alta qualidade com vozes naturais.
                        </p>
                    </div>

                    <div class="relative text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-600 text-2xl font-bold text-white">
                            3
                        </div>
                        <h3 class="mt-6 text-lg font-semibold text-zinc-900 dark:text-white">
                            Download
                        </h3>
                        <p class="mt-2 text-base text-zinc-600 dark:text-zinc-400">
                            Baixe seu audiobook em MP3 e ouça onde e quando quiser.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="bg-white py-24 dark:bg-zinc-900 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-4xl">
                    Planos Simples e Transparentes
                </h2>
                <p class="mt-6 text-lg leading-8 text-zinc-600 dark:text-zinc-400">
                    Escolha o plano ideal para suas necessidades
                </p>
            </div>

            <div class="mx-auto mt-16 grid max-w-lg grid-cols-1 gap-8 lg:max-w-4xl lg:grid-cols-2">
                <!-- Free Plan -->
                <div class="flex flex-col justify-between rounded-3xl bg-white p-8 shadow-xl ring-1 ring-zinc-200 dark:bg-zinc-800 dark:ring-zinc-700">
                    <div>
                        <h3 class="text-lg font-semibold leading-8 text-zinc-900 dark:text-white">Gratuito</h3>
                        <p class="mt-4 text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                            Perfeito para experimentar o serviço
                        </p>
                        <p class="mt-6 flex items-baseline gap-x-1">
                            <span class="text-4xl font-bold tracking-tight text-zinc-900 dark:text-white">R$ 0</span>
                            <span class="text-sm font-semibold leading-6 text-zinc-600 dark:text-zinc-400">/mês</span>
                        </p>
                        <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                            <li class="flex gap-x-3">
                                <svg class="h-6 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                                5 conversões por mês
                            </li>
                            <li class="flex gap-x-3">
                                <svg class="h-6 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                                Até 10.000 caracteres por conversão
                            </li>
                            <li class="flex gap-x-3">
                                <svg class="h-6 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                                3 vozes básicas
                            </li>
                            <li class="flex gap-x-3">
                                <svg class="h-6 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                                Download em MP3
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}"
                       class="mt-8 block rounded-lg bg-zinc-900 px-3.5 py-2.5 text-center text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 transition-colors"
                       wire:navigate>
                        Começar Grátis
                    </a>
                </div>

                <!-- Pro Plan -->
                <div class="flex flex-col justify-between rounded-3xl bg-gradient-to-br from-blue-600 to-violet-600 p-8 shadow-xl ring-1 ring-blue-600">
                    <div>
                        <h3 class="text-lg font-semibold leading-8 text-white">Premium</h3>
                        <p class="mt-4 text-sm leading-6 text-blue-100">
                            Para uso profissional e comercial
                        </p>
                        <p class="mt-6 flex items-baseline gap-x-1">
                            <span class="text-4xl font-bold tracking-tight text-white">R$ 49</span>
                            <span class="text-sm font-semibold leading-6 text-blue-100">/mês</span>
                        </p>
                        <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-blue-100">
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
                                API de integração
                            </li>
                            <li class="flex gap-x-3">
                                <svg class="h-6 w-5 flex-none text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                                Suporte prioritário
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}"
                       class="mt-8 block rounded-lg bg-white px-3.5 py-2.5 text-center text-sm font-semibold text-blue-600 hover:bg-blue-50 transition-colors"
                       wire:navigate>
                        Começar Agora
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-zinc-50 dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-32 lg:px-8">
            <div class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 to-violet-600 px-6 py-24 text-center shadow-2xl sm:px-16">
                <h2 class="mx-auto max-w-2xl text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    Pronto para transformar seus textos em audiobooks?
                </h2>
                <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-blue-100">
                    Comece gratuitamente hoje e descubra como é fácil criar audiobooks de qualidade profissional.
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('register') }}"
                       class="rounded-lg bg-white px-6 py-3 text-base font-semibold text-blue-600 shadow-sm hover:bg-blue-50 transition-colors"
                       wire:navigate>
                        Começar Grátis
                    </a>
                    <a href="#how-it-works" class="text-base font-semibold leading-7 text-white">
                        Saiba mais <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
