@extends('layouts.app')

@section('title', 'Nosotros | Aseguradora Rose')

@section('content')
    <section class="mx-auto max-w-4xl px-6 py-16">
        <p class="section-kicker">Nosotros</p>
        <h1 class="mt-3 text-4xl font-black text-blue-950">Tu tranquilidad es nuestra misión.</h1>
        <p class="mt-6 text-lg leading-8 text-slate-600">En Aseguradora Rose trabajamos para ofrecer protección clara, accesible y cercana. Te ayudamos a elegir coberturas que se adapten a tus necesidades y a las de las personas que más quieres.</p>
        <div class="mt-10 grid gap-5 sm:grid-cols-3">
            <div class="info-card"><strong>Confianza</strong><span>Compromiso en cada atención.</span></div>
            <div class="info-card"><strong>Cercanía</strong><span>Asesoría pensada para ti.</span></div>
            <div class="info-card"><strong>Respuesta</strong><span>Siempre contigo cuando importa.</span></div>
        </div>
    </section>
@endsection
