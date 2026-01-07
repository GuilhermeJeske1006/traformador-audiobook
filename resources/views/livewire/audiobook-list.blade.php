<div class="space-y-6" wire:poll.5s="checkProcessingStatus">
    <div class="rounded-2xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-800">
        <!-- Header -->
        <div class="border-b border-zinc-200 p-6 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-violet-100 p-2 dark:bg-violet-900/30">
                        <svg class="h-6 w-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009 15.553z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Meus Audiobooks</h2>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Gerencie seus audiobooks</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Processing Status Indicator -->
            @if ($hasProcessingAudiobooks)
                <div class="mb-6 flex items-start gap-3 rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                    <svg class="h-5 w-5 flex-shrink-0 animate-spin text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Processando audiobooks...</p>
                        <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">A página está atualizando automaticamente a cada 5 segundos</p>
                    </div>
                </div>
            @endif

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

            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por título ou nome do arquivo..."
                        class="block w-full rounded-lg border border-zinc-300 bg-white py-3 pl-10 pr-4 text-zinc-900 placeholder-zinc-400 shadow-sm transition-all focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white dark:placeholder-zinc-500 dark:focus:border-violet-400"
                    />
                </div>
            </div>

        @if ($audiobooks->count() > 0)
            <div class="space-y-4">
                @foreach ($audiobooks as $audiobook)
                    <div class="group overflow-hidden rounded-xl border border-zinc-200 bg-white transition-all hover:shadow-md dark:border-zinc-700 dark:bg-zinc-800/50">
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-lg bg-gradient-to-br from-blue-100 to-violet-100 p-3 dark:from-blue-900/30 dark:to-violet-900/30">
                                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white truncate">
                                                {{ $audiobook->title }}
                                            </h3>
                                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400 truncate">
                                                {{ $audiobook->original_filename }}
                                            </p>

                                            <!-- Meta Information -->
                                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                                @if ($audiobook->status === 'completed')
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                        </svg>
                                                        Concluído
                                                    </span>
                                                @elseif ($audiobook->status === 'processing')
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                        <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Processando {{ $audiobook->processing_progress }}%
                                                    </span>
                                                @elseif ($audiobook->status === 'failed')
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                                        </svg>
                                                        Falhou
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300">
                                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                                        </svg>
                                                        Pendente
                                                    </span>
                                                @endif

                                                @if ($audiobook->total_characters)
                                                    <span class="flex items-center gap-1.5 text-xs text-zinc-600 dark:text-zinc-400">
                                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                        </svg>
                                                        {{ number_format($audiobook->total_characters) }} caracteres
                                                    </span>
                                                @endif

                                                <span class="flex items-center gap-1.5 text-xs text-zinc-600 dark:text-zinc-400">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $audiobook->created_at->diffForHumans() }}
                                                </span>
                                            </div>

                                            @if ($audiobook->error_message)
                                                <div class="mt-3 flex items-start gap-2 rounded-lg bg-red-50 p-3 dark:bg-red-900/20">
                                                    <svg class="h-4 w-4 flex-shrink-0 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                                    </svg>
                                                    <p class="text-xs text-red-700 dark:text-red-300">
                                                        {{ $audiobook->error_message }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if ($audiobook->isCompleted() && $audiobook->audio_path)
                                                <div class="mt-4">
                                                    <audio controls class="w-full rounded-lg" style="max-width: 500px;">
                                                        <source src="{{ Storage::url($audiobook->audio_path) }}" type="audio/mpeg">
                                                        Seu navegador não suporta o elemento de áudio.
                                                    </audio>
                                                </div>
                                            @endif

                                            <!-- Video Section -->
                                            @if ($audiobook->hasVideo())
                                                <div class="mt-4">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <svg class="h-4 w-4 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0118 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0118 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 016 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M19.125 12h1.5m0 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h1.5m14.25 0h1.5" />
                                                        </svg>
                                                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Vídeo com legendas disponível</span>
                                                    </div>
                                                    <video controls class="w-full rounded-lg" style="max-width: 640px;">
                                                        <source src="{{ Storage::url($audiobook->video_path) }}" type="video/mp4">
                                                        Seu navegador não suporta o elemento de vídeo.
                                                    </video>
                                                </div>
                                            @elseif ($audiobook->isVideoProcessing())
                                                <div class="mt-4 flex items-start gap-2 rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                                                    <svg class="h-4 w-4 flex-shrink-0 animate-spin text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Gerando vídeo com legendas...</p>
                                                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Progresso: {{ $audiobook->video_progress }}%</p>
                                                    </div>
                                                </div>
                                            @elseif ($audiobook->isVideoFailed())
                                                <div class="mt-4 flex items-start gap-2 rounded-lg bg-red-50 p-3 dark:bg-red-900/20">
                                                    <svg class="h-4 w-4 flex-shrink-0 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-red-700 dark:text-red-300">Erro ao gerar vídeo</p>
                                                        @if ($audiobook->video_error_message)
                                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $audiobook->video_error_message }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-shrink-0 flex-col items-end gap-2">
                                    @if ($audiobook->isCompleted() && $audiobook->audio_path)
                                        <a href="{{ Storage::url($audiobook->audio_path) }}"
                                           download
                                           class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Baixar Áudio
                                        </a>
                                    @endif

                                    @if ($audiobook->canGenerateVideo())
                                        <button
                                            wire:click="openVideoCustomizationModal({{ $audiobook->id }})"
                                            class="inline-flex items-center gap-2 rounded-lg bg-violet-50 px-3 py-2 text-sm font-medium text-violet-700 transition-colors hover:bg-violet-100 dark:bg-violet-900/30 dark:text-violet-400 dark:hover:bg-violet-900/50">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0118 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0118 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 016 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M19.125 12h1.5m0 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h1.5m14.25 0h1.5" />
                                            </svg>
                                            Gerar Vídeo
                                        </button>
                                    @endif

                                    @if ($audiobook->hasVideo())
                                        <a href="{{ Storage::url($audiobook->video_path) }}"
                                           download
                                           class="inline-flex items-center gap-2 rounded-lg bg-violet-50 px-3 py-2 text-sm font-medium text-violet-700 transition-colors hover:bg-violet-100 dark:bg-violet-900/30 dark:text-violet-400 dark:hover:bg-violet-900/50">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Baixar Vídeo
                                        </a>
                                    @endif

                                    <button
                                        wire:click="delete({{ $audiobook->id }})"
                                        wire:confirm="Tem certeza que deseja excluir este audiobook?"
                                        class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $audiobooks->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-zinc-300 bg-zinc-50 py-16 dark:border-zinc-600 dark:bg-zinc-900/50">
                <div class="rounded-full bg-gradient-to-br from-blue-100 to-violet-100 p-4 dark:from-blue-900/30 dark:to-violet-900/30">
                    <svg class="h-12 w-12 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                    </svg>
                </div>
                <h3 class="mt-6 text-lg font-semibold text-zinc-900 dark:text-white">
                    @if ($search)
                        Nenhum resultado encontrado
                    @else
                        Nenhum audiobook ainda
                    @endif
                </h3>
                <p class="mt-2 max-w-sm text-center text-sm text-zinc-600 dark:text-zinc-400">
                    @if ($search)
                        Tente ajustar os termos de busca ou limpe o filtro para ver todos os audiobooks.
                    @else
                        Comece fazendo upload do seu primeiro PDF e transforme-o em um audiobook incrível!
                    @endif
                </p>
                @if ($search)
                    <button
                        wire:click="$set('search', '')"
                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Limpar busca
                    </button>
                @endif
            </div>
        @endif
        </div>
    </div>

    <!-- Modal de Personalização de Vídeo -->
    @if ($showVideoCustomizationModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" wire:click="closeVideoCustomizationModal">
            <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-zinc-800" wire:click.stop>
                    <!-- Modal Header -->
                    <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                Personalizar Vídeo
                            </h3>
                            <button wire:click="closeVideoCustomizationModal" class="rounded-lg p-1 text-zinc-400 transition-colors hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-700 dark:hover:text-zinc-300">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4">
                        <div class="space-y-5">
                            <!-- Background Type -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-zinc-900 dark:text-white">
                                    Tipo de Fundo do Vídeo
                                </label>
                                <div class="flex gap-3">
                                    <label class="flex flex-1 cursor-pointer items-center gap-3 rounded-lg border border-zinc-300 bg-white p-3 transition-all hover:border-violet-400 dark:border-zinc-600 dark:bg-zinc-900 dark:hover:border-violet-500">
                                        <input type="radio" wire:model.live="videoBackgroundType" value="gradient" class="h-4 w-4 border-zinc-300 text-violet-600 focus:ring-2 focus:ring-violet-500/20 dark:border-zinc-600 dark:bg-zinc-700">
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">Gradiente</span>
                                    </label>
                                    <label class="flex flex-1 cursor-pointer items-center gap-3 rounded-lg border border-zinc-300 bg-white p-3 transition-all hover:border-violet-400 dark:border-zinc-600 dark:bg-zinc-900 dark:hover:border-violet-500">
                                        <input type="radio" wire:model.live="videoBackgroundType" value="solid" class="h-4 w-4 border-zinc-300 text-violet-600 focus:ring-2 focus:ring-violet-500/20 dark:border-zinc-600 dark:bg-zinc-700">
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">Sólido</span>
                                    </label>
                                </div>
                                @error('videoBackgroundType')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Background Color -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-zinc-900 dark:text-white">
                                    Cor de Fundo
                                </label>
                                <div class="grid grid-cols-5 gap-3">
                                    @foreach($this->backgroundColors as $color => $colorName)
                                        <label class="group relative cursor-pointer">
                                            <input type="radio" wire:model.live="videoBackgroundColor" value="{{ $color }}" class="peer sr-only">
                                            <div class="h-16 w-full rounded-lg border-2 border-transparent transition-all peer-checked:border-violet-600 peer-checked:ring-2 peer-checked:ring-violet-500/20 group-hover:scale-105 dark:peer-checked:border-violet-400" style="background-color: {{ $color }}"></div>
                                            <span class="absolute inset-0 flex items-center justify-center text-sm font-medium text-white opacity-0 peer-checked:opacity-100">✓</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('videoBackgroundColor')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subtitle Style -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-zinc-900 dark:text-white">
                                    Estilo de Legenda
                                </label>
                                <select wire:model.live="subtitleStyle" class="block w-full rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 shadow-sm transition-all focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white dark:focus:border-violet-400">
                                    <option value="default">Padrão (Texto branco com sombra)</option>
                                    <option value="bold">Negrito (Texto mais forte)</option>
                                    <option value="outline">Contorno (Borda ao redor do texto)</option>
                                    <option value="box">Caixa (Fundo semi-transparente)</option>
                                </select>
                                @error('subtitleStyle')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Font Size -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-zinc-900 dark:text-white">
                                    Tamanho da Fonte: <span class="font-semibold text-violet-600 dark:text-violet-400">{{ $subtitleFontSize }}px</span>
                                </label>
                                <input type="range" wire:model.live="subtitleFontSize" min="16" max="48" step="2" class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-zinc-200 accent-violet-600 dark:bg-zinc-700">
                                <div class="mt-1 flex justify-between text-xs text-zinc-500 dark:text-zinc-400">
                                    <span>16px</span>
                                    <span>48px</span>
                                </div>
                                @error('subtitleFontSize')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        <div class="flex justify-end gap-3">
                            <button
                                wire:click="closeVideoCustomizationModal"
                                type="button"
                                class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                                Cancelar
                            </button>
                            <button
                                wire:click="generateVideoWithCustomization"
                                type="button"
                                class="rounded-lg bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all hover:from-violet-700 hover:to-purple-700 hover:shadow-xl">
                                Gerar Vídeo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    @endif
</div>
