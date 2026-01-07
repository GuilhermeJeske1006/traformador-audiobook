<div class="space-y-6">
    <div class="rounded-2xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-800">
        <!-- Header -->
        <div class="border-b border-zinc-200 p-6 dark:border-zinc-700">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-violet-100 p-2 dark:bg-violet-900/30">
                    <svg class="h-6 w-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Personalização</h2>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Configure áudio e vídeo</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            @if (session()->has('success'))
                <div class="mb-6 flex items-start gap-3 rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                    <svg class="h-5 w-5 flex-shrink-0 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            @endif

            <form wire:submit="save" class="space-y-6">
                <!-- Voz do Áudio -->
                <div>
                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">
                        Voz do Narrador
                    </label>
                    <select wire:model="voiceName" class="block w-full rounded-lg border border-zinc-300 bg-white px-4 py-3 text-zinc-900 shadow-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white">
                        @foreach($availableVoices as $voice => $label)
                            <option value="{{ $voice }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">Escolha a voz para narração do audiobook</p>
                </div>

                <!-- Background do Vídeo -->
                <div>
                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">
                        Tipo de Fundo do Vídeo
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex cursor-pointer rounded-lg border-2 p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 {{ $videoBackgroundType === 'gradient' ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/20' : 'border-zinc-200 dark:border-zinc-700' }}">
                            <input type="radio" wire:model="videoBackgroundType" value="gradient" class="sr-only">
                            <div>
                                <div class="text-sm font-medium text-zinc-900 dark:text-white">Gradiente</div>
                                <div class="text-xs text-zinc-600 dark:text-zinc-400">Fundo degradê</div>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg border-2 p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 {{ $videoBackgroundType === 'solid' ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/20' : 'border-zinc-200 dark:border-zinc-700' }}">
                            <input type="radio" wire:model="videoBackgroundType" value="solid" class="sr-only">
                            <div>
                                <div class="text-sm font-medium text-zinc-900 dark:text-white">Sólido</div>
                                <div class="text-xs text-zinc-600 dark:text-zinc-400">Cor única</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Cor do Background -->
                <div>
                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">
                        Cor do Fundo
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($backgroundColors as $color => $name)
                            <label class="relative flex cursor-pointer items-center gap-2 rounded-lg border-2 p-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 {{ $videoBackgroundColor === $color ? 'border-violet-500' : 'border-zinc-200 dark:border-zinc-700' }}">
                                <input type="radio" wire:model="videoBackgroundColor" value="{{ $color }}" class="sr-only">
                                <div class="h-6 w-6 rounded border border-zinc-300 dark:border-zinc-600" style="background-color: {{ $color }}"></div>
                                <div class="text-xs text-zinc-900 dark:text-white">{{ $name }}</div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Estilo de Legenda -->
                <div>
                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">
                        Estilo da Legenda
                    </label>
                    <select wire:model="subtitleStyle" class="block w-full rounded-lg border border-zinc-300 bg-white px-4 py-3 text-zinc-900 shadow-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white">
                        @foreach($subtitleStyles as $style => $label)
                            <option value="{{ $style }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tamanho da Fonte -->
                <div>
                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">
                        Tamanho da Fonte: {{ $subtitleFontSize }}px
                    </label>
                    <input type="range" wire:model.live="subtitleFontSize" min="16" max="48" class="w-full">
                    <div class="flex justify-between text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        <span>16px</span>
                        <span>48px</span>
                    </div>
                </div>

                <!-- Ações -->
                <div class="flex items-center justify-end gap-3 border-t border-zinc-200 dark:border-zinc-700 pt-6">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-violet-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Salvar Personalização
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
