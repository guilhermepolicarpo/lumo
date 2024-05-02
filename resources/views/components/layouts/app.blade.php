<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title.' | '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="mr-3 lg:hidden">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <x-app-brand class="p-5 pt-3" />

            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if($user = auth()->user())
                    <x-menu-separator />

                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">
                        <x-slot:actions>
                            <div class="flex gap-2">
                                <x-theme-toggle class="btn-circle btn-ghost btn-xs"  />
                                <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="Sair" no-wire-navigate link="{{ route('logout') }}" />

                            </div>
                        </x-slot:actions>
                    </x-list-item>

                    <x-menu-separator />
                @endif

                <x-menu-item title="Painel" icon="o-sparkles" link="{{ route('dashboard') }}" />

                <x-menu-item title="Agendamento" icon="o-calendar" link="#" />

                <x-menu-item title="Assistidos" icon="o-users" link="#" />

                <x-menu-sub title="Gestão" icon="o-cog-6-tooth">
                    <x-menu-item title="Centro Espírita" icon="o-home" />
                    <x-menu-item title="Usuários" icon="o-user" link="{{ route('users.index') }}" />
                    <x-menu-item title="Mentores" icon="o-user-group" />
                    <x-menu-item title="Orientações" icon="o-chat-bubble-left" />
                    <x-menu-item title="Fluídicos" icon="o-circle-stack" />
                    <x-menu-item title="Tipos de Atendimento" icon="o-queue-list" />
                </x-menu-sub>

                <x-menu-sub title="Biblioteca" icon="o-building-library">
                    <x-menu-item title="Empréstimos" icon="o-arrows-right-left" />
                    <x-menu-item title="Livros" icon="o-book-open" />
                    <x-menu-item title="Categorias" icon="o-tag" />
                    <x-menu-item title="Autores" icon="o-user-group" />
                    <x-menu-item title="Editoras" icon="o-building-office" />
                </x-menu-sub>

            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />
</body>
</html>
