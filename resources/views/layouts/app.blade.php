
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') | MediSchedule</title>

    <script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    medicare: {
                        50: '#eaf5f4',
                        100: '#d4eae8',
                        600: '#176b70',
                        700: '#105256',
                    },
                },
            },
        },
    };
</script>
</head>

<body class="min-h-screen">

    <div id="sidebar-overlay"
         class="fixed inset-0 z-40 hidden bg-black/40 md:hidden"
         aria-hidden="true"></div>

    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col
                  border-r border-gray-200 bg-white p-6 transition-transform duration-300
                  md:translate-x-0">

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl
                        bg-medicare-600 text-2xl text-white">
                +
            </div>

            <div>
                <span class="block text-xl font-bold tracking-tight text-medicare-700">
                    MediSchedule
                </span>
                <span class="text-xs text-gray-400">MediCare Saúde</span>
            </div>
        </a>

        <!-- Navegação -->
        <div class="mt-10">
            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-widest text-gray-400">
                Menu principal
            </p>

            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                   {{ request()->routeIs('dashboard') ? 'bg-medicare-50 font-semibold text-medicare-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>▦</span>
                    Dashboard
                </a>

                <a href="{{ url('/doctors') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                   {{ request()->is('doctors*') ? 'bg-medicare-50 font-semibold text-medicare-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>♙</span>
                    Médicos
                </a>

                <a href="{{ url('/appointments/create') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                   {{ request()->is('appointments/create') ? 'bg-medicare-50 font-semibold text-medicare-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>＋</span>
                    Agendar consulta
                </a>

                <a href="{{ url('/appointments') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                   {{ request()->is('appointments') || request()->is('appointments/*') ? 'bg-medicare-50 font-semibold text-medicare-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>▣</span>
                    Minhas consultas
                </a>
            </nav>
        </div>

        @if(auth()->user()->role === 'admin')
            <div class="mt-8">
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-widest text-gray-400">
                    Administração
                </p>

                <nav class="space-y-2">
                    <a href="{{ url('/admin/doctors') }}"
                       class="block rounded-xl px-4 py-3 text-gray-500 hover:bg-gray-50">
                        Médicos
                    </a>

                    <a href="{{ url('/admin/specialties') }}"
                       class="block rounded-xl px-4 py-3 text-gray-500 hover:bg-gray-50">
                        Especialidades
                    </a>

                    <a href="{{ url('/admin/appointments') }}"
                       class="block rounded-xl px-4 py-3 text-gray-500 hover:bg-gray-50">
                        Agenda geral
                    </a>
                </nav>
            </div>
        @endif

        <div class="mt-auto border-t border-gray-100 pt-5">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full
                            bg-medicare-50 font-bold text-medicare-700">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ auth()->user()->role === 'admin' ? 'Administrador' : 'Paciente' }}
                    </p>
                </div>
            </div>

            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full rounded-xl px-4 py-3 text-left text-sm
                               text-gray-500 transition hover:bg-red-50 hover:text-red-600">
                    ↪ Sair da conta
                </button>
            </form>
        </div>
    </aside>

  
    <div class="min-h-screen md:ml-72">

        
        <header class="sticky top-0 z-30 flex items-center justify-between gap-4
                       border-b border-gray-200 bg-white/95 px-5 py-4 backdrop-blur
                       sm:px-8">

            <div class="flex items-center gap-3">
                <button id="menu-toggle"
                        type="button"
                        aria-label="Abrir menu"
                        aria-controls="sidebar"
                        aria-expanded="false"
                        class="rounded-xl border border-gray-200 px-3 py-2
                               text-gray-600 hover:bg-gray-50 md:hidden">
                    ☰
                </button>

                <div>
                    <h1 class="font-bold text-gray-800">
                        @yield('page-title', 'Dashboard')
                    </h1>
                    <p class="hidden text-xs text-gray-400 sm:block">
                        MediCare Saúde · Cuidando de você
                    </p>
                </div>
            </div>

            <div class="hidden items-center gap-2 sm:flex">
                <span class="text-sm text-gray-500">Olá,</span>
                <span class="max-w-40 truncate text-sm font-semibold text-medicare-700">
                    {{ auth()->user()->name }}
                </span>
            </div>
        </header>

        <main class="p-5 sm:p-8">

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50
                            p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50
                            p-4 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>
