<div class="space-y-8">
    <!-- Hero Section -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-violet-600 p-8 text-white shadow-xl">
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-white/20 p-3 backdrop-blur-sm">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">
                        Transformador de Audiobook
                    </h1>
                    <p class="mt-1 text-blue-100">
                        Converta seus PDFs em audiobooks com vozes naturais e de alta qualidade
                    </p>
                </div>
            </div>
        </div>

        <!-- Decorative gradient orbs -->
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-16 -left-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">0</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Audiobooks Criados</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/30">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">Rápido</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Processamento</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-violet-100 p-3 dark:bg-violet-900/30">
                    <svg class="h-6 w-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">IA</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Vozes Naturais</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <!-- Upload Section -->
        <div class="lg:col-span-5">
            <livewire:audiobook-upload />
        </div>

        <!-- List Section -->
        <div class="lg:col-span-7">
            <livewire:audiobook-list />
        </div>
    </div>
</div>
