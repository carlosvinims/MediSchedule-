
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Visão geral')

@section('content')

    <!-- Boas-vindas -->
    <section class="mb-8">
        <p class="mb-2 text-sm font-semibold tracking-wide text-medicare-600">
            MEDICARE SAÚDE
        </p>

        <h2 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
            Olá, {{ auth()->user()->name }}!
        </h2>

        <p class="mt-2 text-sm leading-6 text-gray-500 sm:text-base">
            Busque profissionais de acordo com a especialidade desejada.
        </p>
    </section>

    <!-- Pesquisa de médicos -->
    <section class="mb-10 rounded-2xl border border-gray-100
                    bg-white p-6 shadow-sm sm:p-8">

        <h3 class="text-xl font-bold">Encontre seu especialista</h3>

        <form action="{{ route('doctors') }}" method="GET"
              class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-end">

            <div class="flex-1">
                <label for="specialty"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Especialidade médica
                </label>

                <select id="specialty" name="specialty"
                        class="w-full rounded-xl border border-gray-300
                               bg-white px-4 py-3 outline-none
                               focus:border-medicare-600 focus:ring-2
                               focus:ring-medicare-100">

                    <option value="">Todas as especialidades</option>

                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}"
                            @selected((string) request('specialty') === (string) $specialty->id)>
                            {{ $specialty->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <button type="submit"
                    class="rounded-xl bg-medicare-600 px-6 py-3
                           font-medium text-white transition hover:bg-medicare-700">
                Buscar médicos
            </button>
        </form>
    </section>

    <!-- Corpo médico -->
    <section>
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h3 class="text-xl font-bold">Nosso corpo médico</h3>

                <p class="mt-1 text-sm text-gray-500">
                    Profissionais preparados para cuidar de você.
                </p>
            </div>

        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

            @forelse($doctors as $doctor)

                <article class="flex flex-col rounded-2xl border border-gray-100
                                bg-white p-5 shadow-sm transition hover:shadow-md">

                    <div class="mb-4 flex items-center gap-3">

                        <div class="flex h-14 w-14 shrink-0 items-center
                                    justify-center rounded-2xl bg-medicare-50
                                    text-2xl text-medicare-700">
                            ♙
                        </div>

                        <div class="min-w-0">
                            <h4 class="truncate font-bold text-gray-900">
                                {{ $doctor->name }}
                            </h4>

                            <p class="mt-1 text-sm text-medicare-700">
                                {{ $doctor->specialty->name ?? 'Especialidade não informada' }}
                            </p>

                        </div>
                    </div>

                    <div class="mb-5 space-y-2 text-sm text-gray-500">

                        <p>
                            <span class="font-medium text-gray-700">CRM:</span>
                            {{ $doctor->crm }}
                        </p>

                        <p>
                            <span class="font-medium text-gray-700">Atendimento:</span>
                            {{ substr($doctor->work_start_time, 0, 5) }}
                            às
                            {{ substr($doctor->work_end_time, 0, 5) }}
                        </p>

                        <p>
                            <span class="font-medium text-gray-700">Duração:</span>
                            {{ $doctor->slot_duration }} minutos
                        </p>
                        <p>
                            <span class="font-medium text-gray-700">Descrição:</span>
                            {{ $doctor->specialty->description ?? 'Descrição não informada' }}
                        </p>

                    </div>

                    <a href="{{ 'appointments/create' }}"
                       class="mt-auto inline-flex items-center justify-center
                              rounded-xl border border-medicare-600 px-4 py-3
                              text-sm font-semibold text-medicare-700
                              transition hover:bg-medicare-600 hover:text-white">
                        Consultar horários →
                    </a>

                </article>

            @empty

                <div class="col-span-full rounded-2xl border border-dashed
                            border-gray-300 bg-white p-10 text-center">

                    <div class="mb-3 text-4xl text-gray-300">♙</div>

                    <h4 class="font-bold text-gray-800">
                        Nenhum médico encontrado
                    </h4>

                    <p class="mt-2 text-sm text-gray-500">
                        Não há médicos disponíveis para o filtro selecionado.
                    </p>

                </div>

            @endforelse

        </div>
    </section>

@endsection
