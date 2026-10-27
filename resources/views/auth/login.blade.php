
<!DOCTYPE html>
<html lang="ptS-BR">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>login - MediCare Saúde</title>
  </head>
  <body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
      <div class="relative flex flex-col m-6 space-y-8 bg-white shadow-2xl rounded-2xl md:flex-row md:space-y-0">
        <div class="flex flex-col justify-center p-8 md:p-14">
          <span class="mb-3 text-4xl font-bold">Bem-vindo de volta</span>
          <span class="font-light text-gray-400 mb-8">
            Bem-vindo de volta! Por favor, insira seus dados.
          </span>
          <form action="{{ route('login') }}" method="POST">
            @csrf
          <div class="py-4">
            <span class="mb-2 text-md">Email</span>
            <input type="email" class="w-full p-2 border border-gray-300 rounded-md placeholder:font-light
            w-full p-2 border rounded-md @error('email') border-red-500 @else border-gray-300 @enderror" required autofocus name="email" id="email"/>
             @error('email')
            <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                @enderror
          </div>
          <div class="py-4">
            <span class="mb-2 text-md">Senha</span>
            <input type="password" name="password" id="pass" class="w-full p-2 border border-gray-300 rounded-md placeholder:font-light placeholder:text-gray-500" required/>
          </div>
          <div class="flex justify-between w-full py-4">
            <div class="mr-24">
              <input type="checkbox" name="remember" id="remember" class="mr-2" />
              <span class="text-md">Lembrar de mim</span>
            </div>
          </div>
          <button class="w-full bg-black text-white p-2 rounded-lg mb-6 hover:bg-white hover:text-black hover:border hover:border-gray-300"
          type="submit"> Entrar
          </button>
          </form>
          <div class="text-center text-gray-400">
            Não tem uma conta?
            <span class="font-bold text-black"><a href="{{ route('register') }}">Cadastre-se gratuitamente</a></span>
          </div>
        </div>
        
        <div class="relative">
          <img src="{{ asset('images/image.png') }}" class="w-[400px] h-full hidden rounded-r-2xl md:block object-cover"/>
          <div class="absolute hidden bottom-10 left-6 right-6 p-6 bg-white bg-opacity-30 backdrop-blur-sm rounded drop-shadow-lg md:block ">
            <span class="text-white text-xl"><strong>Sua saúde, nosso cuidado!</strong><br />Agende suas consultas na MediCare Saúde de forma rápida, simples e prática.
            <br />Mais facilidade para você cuidar do que realmente importa.
            </span>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
