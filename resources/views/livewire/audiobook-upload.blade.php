<div class="sticky top-24 space-y-6">
    <!-- Upload Card -->
    <div class="rounded-2xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-800">
        <!-- Header -->
        <div class="border-b border-zinc-200 p-6 dark:border-zinc-700">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Novo Audiobook</h2>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Faça upload de um PDF</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-6">
            @if (session()->has('success'))
                <div class="mb-6 flex items-start gap-3 rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                    <svg class="h-5 w-5 flex-shrink-0 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 flex items-start gap-3 rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
                    <svg class="h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            @endif

            <form wire:submit="save" class="space-y-6">
                <!-- Title Input -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-900 dark:text-white">
                        Título do Audiobook
                    </label>
                    <input
                        type="text"
                        wire:model="title"
                        placeholder="Ex: O Pequeno Príncipe"
                        required
                        class="block w-full rounded-lg border border-zinc-300 bg-white px-4 py-3 text-zinc-900 placeholder-zinc-400 shadow-sm transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white dark:placeholder-zinc-500 dark:focus:border-blue-400"
                    />
                    @error('title')
                        <p class="mt-2 flex items-center gap-1 text-sm text-red-600 dark:text-red-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-900 dark:text-white">
                        Arquivo PDF
                    </label>

                    <div class="relative">
                        <input
                            type="file"
                            wire:model="pdfFile"
                            accept=".pdf"
                            id="pdf-upload"
                            class="hidden"
                        />
                        <label
                            for="pdf-upload"
                            class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-zinc-300 bg-zinc-50 px-6 py-8 transition-all hover:border-blue-400 hover:bg-blue-50/50 dark:border-zinc-600 dark:bg-zinc-900/50 dark:hover:border-blue-500 dark:hover:bg-blue-900/10"
                        >
                            <svg class="mb-3 h-10 w-10 text-zinc-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <p class="mb-1 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Clique para selecionar o arquivo PDF
                            </p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                Formato aceito: PDF (máx. 10MB)
                            </p>
                        </label>
                    </div>

                    @error('pdfFile')
                        <p class="mt-2 flex items-center gap-1 text-sm text-red-600 dark:text-red-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror

                    @if ($pdfFile)
                        <div class="mt-3 flex items-center gap-3 rounded-lg border border-zinc-200 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-800">
                            <div class="rounded-md bg-blue-100 p-2 dark:bg-blue-900/30">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $pdfFile->getClientOriginalName() }}</p>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400">Pronto para upload</p>
                            </div>
                        </div>
                    @endif

                    <div wire:loading wire:target="pdfFile" class="mt-3">
                        <div class="flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400">
                            <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Carregando arquivo...
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="w-full rounded-lg bg-gradient-to-r from-blue-600 to-violet-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:from-blue-700 hover:to-violet-700 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="save" class="flex items-center justify-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                        Converter em Audiobook
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center justify-center gap-2">
                        <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processando...
                    </span>
                </button>
            </form>
        </div>
    </div>

    <!-- Info Card -->
    <div class="rounded-xl border border-blue-200 bg-blue-50 p-6 dark:border-blue-900/50 dark:bg-blue-900/10">
        <div class="flex gap-3">
            <svg class="h-6 w-6 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <div>
                <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-300">Dicas para melhores resultados</h3>
                <ul class="mt-2 space-y-1 text-sm text-blue-800 dark:text-blue-400">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 dark:text-blue-500">•</span>
                        Use PDFs com texto selecionável
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 dark:text-blue-500">•</span>
                        Evite PDFs escaneados (imagens)
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 dark:text-blue-500">•</span>
                        Arquivos menores processam mais rápido
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
