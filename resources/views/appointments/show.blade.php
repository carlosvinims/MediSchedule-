@extends('layouts.app')

@section('title', 'appointment.index')
@section('page-title', 'Visão geral')

@section('content')

<section>
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h3 class="text-xl font-bold">Histórico de consultas</h3>

            </div>

        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

            @forelse($appointments as $appointment)

                <article class="flex flex-col rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">

                    <div class="mb-4 flex items-center gap-3">

                        <div class="flex h-14 w-14 shrink-0 items-center
                                    justify-center rounded-2xl bg-medicare-50
                                    text-2xl text-medicare-700">
                            ♙
                        </div>

                        <div class="min-w-0">
                            <h4 class="truncate font-bold text-gray-900">
                                {{ $appointment->doctor->name }}
                            </h4>

                            <p class="mt-1 text-sm text-medicare-700">
                                {{ $appointment->doctor->specialty->name ?? 'Especialidade não informada' }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-5 space-y-2 text-sm text-gray-500">

                        <p>
                            <span class="font-medium text-gray-700">Status:</span>
                            {{ $statuses[$appointment->status] ?? $appointment->status }}
                        </p>

                        <p>
                            <span class="font-medium text-gray-700">Data:</span>
                            {{ $appointment->appointment_date }}
                        </p>

                        <p>
                            <span class="font-medium text-gray-700">Motivo da consulta:</span>
                            {{ $appointment->notes ?? 'Motivo não informado.' }}
                        </p>

                    </div>

                    <a href="{{ route('appointments.show', $appointment) }}"
                       class="mt-auto inline-flex items-center justify-center
                              rounded-xl border border-medicare-600 px-4 py-3
                              text-sm font-semibold text-medicare-700
                              transition hover:bg-medicare-600 hover:text-white">
                        Mais Detalhes
                    </a>


                     @if ($appointment->status === 'scheduled')

        <form action="{{ route('appointments.destroy', $appointment) }}"
              method="POST"
              onsubmit="return confirm('Tem certeza de que deseja cancelar esta consulta?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="inline-flex w-full items-center justify-center
                           rounded-xl border border-red-200 px-4 py-3
                           text-sm font-semibold text-red-600
                           transition hover:bg-red-100">
                Cancelar consulta
            </button>
        </form>

    @endif
                </article>

            @empty

                <div class="col-span-full rounded-2xl border border-dashed
                            border-gray-300 bg-white p-10 text-center">

                    <div class="mb-3 text-4xl text-gray-300">▣</div>

                    <h4 class="font-bold text-gray-800">
                        Nenhuma consulta encontrada
                    </h4>

                     <p class="mt-2 text-sm text-gray-500">
                        Não há consultas para o filtro selecionado.
                    </p>
                </div>

            @endforelse

        </div>
    </section>

@endsection