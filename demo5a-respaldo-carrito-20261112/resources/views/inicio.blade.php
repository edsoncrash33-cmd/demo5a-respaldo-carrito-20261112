@extends('layouts.app')

@section('title', 'Inicio | Aseguradora Rose')

@section('content')
    <section class="hero-pattern text-white">
        <div class="mx-auto flex max-w-7xl flex-col items-center px-6 py-20 text-center sm:py-28">
            <div class="hero-shield" aria-hidden="true">♢</div>
            <p class="mb-4 font-semibold uppercase tracking-[0.3em] text-blue-100">Protección para lo que más importa</p>
            <h1 class="max-w-4xl text-4xl font-black leading-tight sm:text-6xl">Bienvenido a Aseguradora Rose</h1>
            <p class="mt-6 max-w-3xl text-lg text-blue-100">Seguros confiables, atención personalizada y soluciones para ti, tu familia y tus bienes.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('catalogo') }}" class="hero-button">Ver paquetes</a>
                <a href="{{ route('contacto') }}" class="hero-button hero-button-outline">Contáctanos</a>
            </div>
        </div>
    </section>
    <section class="mx-auto max-w-7xl px-6 py-16">
        <div class="mb-10 text-center"><p class="section-kicker">Soluciones para ti</p><h2 class="section-title">Nuestros servicios</h2></div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([['♢', 'Seguro de Vida', 'Protege el futuro de tu familia.'], ['▣', 'Seguro de Auto', 'Conduce con respaldo en cada trayecto.'], ['✚', 'Gastos Médicos', 'Cuida tu salud con atención oportuna.'], ['⌂', 'Seguro de Casa', 'Protege tu hogar y tus pertenencias.']] as [$icon, $title, $description])
                <article class="service-card"><div class="service-icon">{{ $icon }}</div><h3>{{ $title }}</h3><p>{{ $description }}</p></article>
            @endforeach
        </div>
    </section>
@endsection
