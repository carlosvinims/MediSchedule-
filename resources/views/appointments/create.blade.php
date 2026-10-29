
@extends('layouts.app')

@section('title', 'Agendar consulta')
@section('page-title', 'Agendar consulta')

@section('content')

    <!-- Cabeçalho -->
    <section class="mb-8">
        <p class="mb-2 text-sm font-semibold tracking-wide text-medicare-600">
            MEDICARE SAÚDE
        </p>

        <h2 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
            Vamos agendar sua consulta?
        </h2>

        <p class="mt-2 text-sm leading-6 text-gray-500 sm:text-base">
            Escolha um profissional e consulte os horários disponíveis
            para agendar seu atendimento.
        </p>
    </section>

    <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-3">

        <!-- Formulário principal -->
        <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8 xl:col-span-2">

            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-medicare-50 text-2xl text-medicare-700">
                    +
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        Dados do agendamento
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Selecione o médico e a data desejada.
                    </p>
                </div>
            </div>

            <!-- Etapa 1: consultar disponibilidade -->
            <form
                action="{{ route('appointments.create') }}"
                method="GET"
                class="space-y-5"
            >
                <div>
                    <label for="doctor_id"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Médico
                    </label>

                    <select
                        id="doctor_id"
                        name="doctor_id"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 outline-none transition focus:border-medicare-600 focus:ring-2 focus:ring-medicare-100"
                    >
                        <option value="">Selecione um médico</option>

                        @foreach ($doctors as $doctor)
                            <option
                                value="{{ $doctor->id }}"
                                @selected((string) $selectedDoctorId === (string) $doctor->id)
                            >
                                {{ $doctor->name }}
                                — {{ $doctor->specialty->name ?? 'Especialidade não informada' }}
                            </option>
                        @endforeach
                    </select>

                    @error('doctor_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($doctors->isEmpty())
                        <p class="mt-2 text-sm text-amber-700">
                            Nenhum médico está disponível no momento.
                        </p>
                    @endif
                </div>

                <div>
                    <label for="appointment_day"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Data da consulta
                    </label>

                    <input
                        type="date"
                        id="appointment_day"
                        name="appointment_day"
                        min="{{ now()->format('Y-m-d') }}"
                        value="{{ $selectedDate }}"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 outline-none transition focus:border-medicare-600 focus:ring-2 focus:ring-medicare-100"
                    >

                    @error('appointment_day')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    @disabled($doctors->isEmpty())
                    class="inline-flex w-full items-center justify-center rounded-xl bg-medicare-600 px-6 py-3 font-medium text-white transition hover:bg-medicare-700 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                >
                    Consultar horários
                </button>
            </form>

            <!-- Etapa 2: selecionar horário e confirmar -->
            @if ($selectedDoctorId && $selectedDate)

                <div class="my-7 border-t border-gray-100"></div>

                <div class="mb-5">
                    <h3 class="text-lg font-bold text-gray-900">
                        Horários disponíveis
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-gray-500">
                        Selecione um dos horários livres para continuar.
                    </p>
                </div>

                @if ($availabilityError)
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        {{ $availabilityError }}
                    </div>

                @elseif (count($availableSlots) === 0)
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <h4 class="font-semibold text-amber-800">
                            Nenhum horário disponível
                        </h4>

                        <p class="mt-1 text-sm leading-6 text-amber-700">
                            Não encontramos horários livres para esse médico
                            nessa data. Experimente selecionar outro dia.
                        </p>
                    </div>

                @else
                    <div class="mb-5 rounded-xl border border-medicare-100 bg-medicare-50 p-4">
                        <p class="text-sm leading-6 text-medicare-700">
                            Encontramos
                            <strong>{{ count($availableSlots) }}</strong>
                            {{ count($availableSlots) === 1 ? 'horário disponível' : 'horários disponíveis' }}.
                            Os horários respeitam o expediente e a duração
                            das consultas do profissional.
                        </p>
                    </div>

                    <form
                        action="{{ route('appointments.store') }}"
                        method="POST"
                        class="space-y-6"
                    >
                        @csrf

                        <input
                            type="hidden"
                            name="doctor_id"
                            value="{{ $selectedDoctorId }}"
                        >

                        <input
                            type="hidden"
                            name="appointment_day"
                            value="{{ $selectedDate }}"
                        >

                        <div>
                            <label for="appointment_time"
                                   class="mb-2 block text-sm font-medium text-gray-700">
                                Escolha o horário
                            </label>

                            <select
                                id="appointment_time"
                                name="appointment_time"
                                required
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 outline-none transition focus:border-medicare-600 focus:ring-2 focus:ring-medicare-100"
                            >
                                <option value="">Selecione um horário</option>

                                @foreach ($availableSlots as $slot)
                                    <option
                                        value="{{ $slot }}"
                                        @selected(old('appointment_time') === $slot)
                                    >
                                        {{ $slot }}
                                    </option>
                                @endforeach
                            </select>

                            @error('appointment_time')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes"
                                   class="mb-2 block text-sm font-medium text-gray-700">
                                Motivo da consulta
                                <span class="font-normal text-gray-400">
                                    (opcional)
                                </span>
                            </label>

                            <textarea
                                id="notes"
                                name="notes"
                                rows="4"
                                maxlength="2000"
                                placeholder="Descreva brevemente o motivo da consulta ou o que gostaria de relatar ao médico..."
                                class="w-full resize-y rounded-xl border border-gray-300 px-4 py-3 text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-medicare-600 focus:ring-2 focus:ring-medicare-100"
                            >{{ old('notes') }}</textarea>

                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                            <p class="mt-2 text-xs leading-5 text-gray-400">
                                Você pode deixar este campo em branco.
                            </p>
                        </div>

                        <div class="rounded-xl border border-medicare-100 bg-medicare-50 p-4">
                            <h4 class="text-sm font-semibold text-medicare-700">
                                Antes de confirmar
                            </h4>

                            <p class="mt-1 text-sm leading-5 text-medicare-700">
                                Confira a data e o horário selecionados.
                                O sistema verificará novamente a disponibilidade
                                antes de registrar a consulta.
                            </p>
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">

                            <a
                                href="{{ route('appointments.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-3 font-medium text-gray-700 transition hover:bg-gray-50"
                            >
                                Voltar
                            </a>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-medicare-600 px-6 py-3 font-medium text-white transition hover:bg-medicare-700"
                            >
                                Confirmar agendamento
                            </button>

                        </div>
                    </form>
                @endif
            @endif
        </section>

        <!-- Informações laterais -->
        <aside class="space-y-5">

            <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-medicare-50 text-xl text-medicare-700">
                    i
                </div>

                <h3 class="font-bold text-gray-900">
                    Como funciona?
                </h3>

                <p class="mt-2 text-sm leading-6 text-gray-500">
                    1. Escolha um médico.
                    <br>
                    2. Selecione a data.
                    <br>
                    3. Consulte os horários livres.
                    <br>
                    4. Escolha um horário e confirme.
                </p>
            </article>

            <article class="rounded-2xl border border-medicare-100 bg-medicare-50 p-6">
                <h3 class="font-bold text-medicare-700">
                    Precisa cancelar?
                </h3>

                <p class="mt-2 text-sm leading-6 text-medicare-700">
                    Consulte seus agendamentos e confira as condições
                    de cancelamento na página Minhas consultas.
                </p>

                <a
                    href="{{ route('appointments.index') }}"
                    class="mt-4 inline-flex items-center text-sm font-semibold text-medicare-700 hover:underline"
                >
                    Ver minhas consultas →
                </a>
            </article>

        </aside>
    </div>

@endsection
