<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        @livewireStyles
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900">
        <!-- Navbar -->
        <nav class="sticky top-0 z-50 border-b border-zinc-200 bg-white/80 backdrop-blur-lg dark:border-zinc-800 dark:bg-zinc-900/80">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3" wire:navigate>
                            <x-app-logo />
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex md:items-center md:space-x-8">
                        <a href="{{ route('home') }}"
                           class="text-sm font-medium {{ request()->routeIs('home') ? 'text-zinc-900 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white' }} transition-colors"
                           wire:navigate>
                            Início
                        </a>

                        @auth
                            <a href="{{ route('app') }}"
                               class="text-sm font-medium {{ request()->routeIs('app') ? 'text-zinc-900 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white' }} transition-colors"
                               wire:navigate>
                                Aplicação
                            </a>
                           
                        @endauth

                        <a href="#features"
                           class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                            Recursos
                        </a>

                        <a href="#how-it-works"
                           class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                            Como Funciona
                        </a>

                        <a href="#pricing"
                           class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                            Preços
                        </a>
                    </div>

                    <!-- Desktop User Menu / Auth Buttons -->
                    <div class="hidden md:flex md:items-center md:space-x-4">
                        @auth
                            <flux:dropdown position="top" align="end">
                                <flux:profile
                                    class="cursor-pointer"
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <flux:menu>
                                    <flux:menu.radio.group>
                                        <div class="p-0 text-sm font-normal">
                                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                                        {{ auth()->user()->initials() }}
                                                    </span>
                                                </span>
                                                <div class="grid flex-1 text-start text-sm leading-tight">
                                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </flux:menu.radio.group>

                                    <flux:menu.separator />

                                    <flux:menu.radio.group>
                                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>Configurações</flux:menu.item>
                                    </flux:menu.radio.group>

                                    <flux:menu.separator />

                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                                            Sair
                                        </flux:menu.item>
                                    </form>
                                </flux:menu>
                            </flux:dropdown>
                        @else
                            <a href="{{ route('login') }}"
                               class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors"
                               wire:navigate>
                                Entrar
                            </a>
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 transition-colors"
                               wire:navigate>
                                Começar Grátis
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex md:hidden">
                        <button type="button"
                                x-data
                                @click="$dispatch('toggle-mobile-menu')"
                                class="inline-flex items-center justify-center rounded-md p-2 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-data="{ open: false }"
                 @toggle-mobile-menu.window="open = !open"
                 x-show="open"
                 x-transition
                 class="md:hidden">
                <div class="space-y-1 border-t border-zinc-200 px-4 pb-3 pt-2 dark:border-zinc-800">
                    <a href="{{ route('home') }}"
                       class="block rounded-md px-3 py-2 text-base font-medium {{ request()->routeIs('home') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white' }}"
                       wire:navigate>
                        Início
                    </a>

                    @auth
                        <a href="{{ route('app') }}"
                           class="block rounded-md px-3 py-2 text-base font-medium {{ request()->routeIs('app') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white' }}"
                           wire:navigate>
                            Aplicação
                        </a>
                    
                    @endauth

                    <a href="#features"
                       class="block rounded-md px-3 py-2 text-base font-medium text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white">
                        Recursos
                    </a>

                    <a href="#how-it-works"
                       class="block rounded-md px-3 py-2 text-base font-medium text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white">
                        Como Funciona
                    </a>

                    <a href="#pricing"
                       class="block rounded-md px-3 py-2 text-base font-medium text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white">
                        Preços
                    </a>

                    @auth
                        <div class="border-t border-zinc-200 pt-4 dark:border-zinc-800">
                            <div class="flex items-center px-3">
                                <div class="flex-shrink-0">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-200 text-sm font-medium text-zinc-900 dark:bg-zinc-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-base font-medium text-zinc-900 dark:text-white">{{ auth()->user()->name }}</div>
                                    <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                            <div class="mt-3 space-y-1">
                                <a href="{{ route('profile.edit') }}"
                                   class="block rounded-md px-3 py-2 text-base font-medium text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white"
                                   wire:navigate>
                                    Configurações
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full rounded-md px-3 py-2 text-left text-base font-medium text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white">
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="border-t border-zinc-200 pt-4 dark:border-zinc-800">
                            <a href="{{ route('login') }}"
                               class="block rounded-md px-3 py-2 text-base font-medium text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white"
                               wire:navigate>
                                Entrar
                            </a>
                            <a href="{{ route('register') }}"
                               class="mt-1 block rounded-md bg-zinc-900 px-3 py-2 text-base font-medium text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
                               wire:navigate>
                                Começar Grátis
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="border-t border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                    <div class="col-span-1 md:col-span-2">
                        <x-app-logo class="mb-4" />
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                            Transforme seus livros em audiobooks com tecnologia de ponta em Text-to-Speech.
                        </p>
                    </div>

                    <div>
                        <h3 class="mb-4 text-sm font-semibold text-zinc-900 dark:text-white">Produto</h3>
                        <ul class="space-y-2">
                            <li><a href="#features" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Recursos</a></li>
                            <li><a href="#how-it-works" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Como Funciona</a></li>
                            <li><a href="#pricing" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Preços</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 text-sm font-semibold text-zinc-900 dark:text-white">Empresa</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Sobre</a></li>
                            <li><a href="#" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Contato</a></li>
                            <li><a href="#" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Privacidade</a></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 border-t border-zinc-200 pt-8 dark:border-zinc-800">
                    <p class="text-center text-sm text-zinc-600 dark:text-zinc-400">
                        &copy; {{ date('Y') }} Transformador de Audiobook. Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </footer>

        @fluxScripts
        @livewireScripts
    </body>
</html>
