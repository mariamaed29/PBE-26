<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Acesso &mdash; {{ config('app.name', 'SAFE') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-gray-50 font-sans text-gray-900 antialiased">
        <main class="min-h-screen lg:grid lg:grid-cols-[minmax(360px,0.9fr)_minmax(520px,1.1fr)]">
            <section class="relative hidden overflow-hidden bg-slate-900 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="absolute inset-x-0 top-0 h-1 bg-blue-500"></div>
                <div class="px-10 pt-10">
                    <a href="/" class="inline-flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold leading-none">SAFE</p>
                            <p class="mt-1 text-xs text-slate-400">Controle de Acesso</p>
                        </div>
                    </a>

                    <div class="mt-20 max-w-md">
                        <p class="text-sm font-semibold uppercase tracking-wider text-blue-300">Portal interno</p>
                        <h1 class="mt-4 text-4xl font-bold leading-tight">Gest&atilde;o escolar simples, segura e organizada.</h1>
                        <p class="mt-4 text-sm leading-6 text-slate-300">
                            Acompanhe ocorr&ecirc;ncias, autoriza&ccedil;&otilde;es, notifica&ccedil;&otilde;es e confirma&ccedil;&otilde;es da portaria em um fluxo &uacute;nico.
                        </p>
                    </div>
                </div>

                <div class="px-10 pb-10">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                            <p class="text-2xl font-bold text-white">AQV</p>
                            <p class="mt-1 text-xs text-slate-400">Registros e aprova&ccedil;&otilde;es</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                            <p class="text-2xl font-bold text-white">Portaria</p>
                            <p class="mt-1 text-xs text-slate-400">Confirma&ccedil;&otilde;es</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                            <p class="text-2xl font-bold text-white">Docentes</p>
                            <p class="mt-1 text-xs text-slate-400">Avisos da turma</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 lg:px-12">
                <div class="w-full max-w-md">
                    <div class="mb-8 flex items-center gap-3 lg:hidden">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold leading-none text-gray-900">SAFE</p>
                            <p class="mt-1 text-xs text-gray-500">Controle de Acesso</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                        {{ $slot }}
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
