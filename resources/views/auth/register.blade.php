
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - MediCare Saúde</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">

        <div class="flex flex-col md:flex-row bg-white shadow-2xl rounded-2xl overflow-hidden w-full max-w-5xl">

            <div class="flex flex-col justify-center p-8 md:p-10 w-full md:w-3/5">

                <h1 class="text-3xl font-bold mb-2">
                    Crie sua conta
                </h1>

                <p class="text-gray-400 font-light mb-6">
                    Faça parte da MediCare Saúde e agende suas consultas com facilidade.
                </p>

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-sm font-medium">
                            Nome completo
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400"
                            placeholder="Digite seu nome completo"
                            autocomplete="name"
                            required
                        >
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block mb-2 text-sm font-medium">
                            E-mail
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400"
                            placeholder="seuemail@exemplo.com"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block mb-2 text-sm font-medium">
                            Celular ou telefone
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            id="phone"
                            value="{{ old('phone') }}"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400"
                            placeholder="Somente números"
                            autocomplete="tel"
                            inputmode="numeric"
                            required
                        >
                        @error('phone')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="cpf" class="block mb-2 text-sm font-medium">
                            CPF
                        </label>
                        <input
                            type="text"
                            name="cpf"
                            id="cpf"
                            value="{{ old('cpf') }}"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400"
                            placeholder="Digite seu CPF"
                            inputmode="numeric"
                            maxlength="14"
                            required
                        >
                        @error('cpf')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block mb-2 text-sm font-medium">
                            Senha
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400"
                            placeholder="Crie uma senha"
                            autocomplete="new-password"
                            required
                        >
                        @error('password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium">
                            Confirmar senha
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400"
                            placeholder="Digite a senha novamente"
                            autocomplete="new-password"
                            required
                        >
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-black text-white p-3 rounded-lg mb-5 hover:bg-white hover:text-black hover:border hover:border-gray-300 transition duration-200"
                    >
                        Criar conta
                    </button>

                </form>

                <p class="text-center text-gray-400 text-sm">
                    Já tem uma conta?
                    <a href="{{ route('login') }}"
                       class="font-bold text-black hover:underline">
                        Entrar
                    </a>
                </p>

            </div>

            <div class="relative hidden md:block md:w-2/5">
                <img
                    src="{{ asset('images/image.png') }}"
                    alt="MediCare Saúde"
                    class="absolute inset-0 w-full h-full object-cover"
                >

                <div class="absolute inset-0 bg-black/30"></div>

                <div class="absolute bottom-8 left-6 right-6 p-5 bg-white/20 backdrop-blur-sm rounded-xl">
                    <h2 class="text-white text-2xl font-bold mb-2">
                        Sua saúde, nosso cuidado!
                    </h2>
                    <p class="text-white text-sm leading-relaxed">
                        Agende suas consultas na MediCare Saúde de forma rápida,
                        simples e prática. Mais facilidade para cuidar do que
                        realmente importa.
                    </p>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
