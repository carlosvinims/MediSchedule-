
@extends('layouts.app')

@section('title', 'home')
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
            Sua saúde, nosso cuidado. O que você precisa hoje?
        </p>
    </section>

    <!-- Atalhos principais -->
    <section class="mb-10 grid grid-cols-1 gap-5 sm:grid-cols-2">

        <article class="rounded-2xl border border-gray-100 bg-white p-6
                        shadow-sm transition hover:-translate-y-1 hover:shadow-md">

            <div class="mb-5 flex h-12 w-12 items-center justify-center
                        rounded-2xl bg-medicare-50 text-2xl text-medicare-700">
                +
            </div>

            <h3 class="text-lg font-bold">Agende sua consulta</h3>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                Encontre um especialista e escolha o melhor horário para você.
            </p>

            <a href="{{ 'doctors.index' }}"
               class="mt-5 inline-flex w-full items-center justify-center
                      rounded-xl bg-medicare-600 px-5 py-3 font-medium
                      text-white transition hover:bg-medicare-700 sm:w-auto">
                Encontrar médico →
            </a>
        </article>

        <article class="rounded-2xl border border-gray-100 bg-white p-6
                        shadow-sm transition hover:-translate-y-1 hover:shadow-md">

            <div class="mb-5 flex h-12 w-12 items-center justify-center
                        rounded-2xl bg-indigo-50 text-2xl text-indigo-600">
                ▣
            </div>

            <h3 class="text-lg font-bold">Minhas consultas</h3>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                Acompanhe seus agendamentos e consulte seu histórico.
            </p>

            <a href="{{ url('/appointments') }}"
               class="mt-5 inline-flex w-full items-center justify-center
                      rounded-xl border border-gray-200 px-5 py-3
                      font-medium text-gray-700 transition hover:bg-gray-50 sm:w-auto">
                Ver minhas consultas →
            </a>
        </article>

    </section>

@endsection
