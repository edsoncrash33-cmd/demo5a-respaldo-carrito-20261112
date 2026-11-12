@extends('layouts.app')

@section('title', 'Detalle del producto | Aseguradora Rose')

@section('content')
    <section class="mx-auto max-w-6xl px-6 py-12">
        @if($error)
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-red-800" role="alert">
                <p class="font-bold">No pudimos cargar el producto.</p>
                <p class="mt-1">{{ $error }}</p>
                <a href="{{ route('catalogo') }}" class="mt-4 inline-block font-bold underline">Volver al catálogo</a>
            </div>
        @else
            <a href="{{ route('catalogo') }}" class="font-semibold text-orange-700 hover:underline">← Volver al catálogo</a>
            <div class="mt-8 grid gap-10 lg:grid-cols-2">
                <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                    @for($i = 0; $i < 3; $i++)
                        @if(isset($producto->imagenes[$i]))
                            <div class="image-frame h-64 rounded-2xl bg-blue-50 lg:h-80">
                                <span class="image-skeleton" aria-hidden="true"></span>
                                <img src="{{ $producto->imagenes[$i] }}" alt="{{ $producto->nombre }}" loading="lazy" class="image-loading h-full w-full rounded-2xl object-cover" onload="this.classList.add('is-loaded')" onerror="this.previousElementSibling.remove(); this.remove()">
                            </div>
                        @else
                            <div class="image-placeholder image-placeholder-large"><span aria-hidden="true">♢</span><small>Imagen {{ $i + 1 }}</small></div>
                        @endif
                    @endfor
                </div>
                <div>
                    <p class="section-kicker">Detalle del producto</p>
                    <h1 class="mt-3 text-4xl font-black text-blue-950">{{ $producto->nombre ?? 'Paquete' }}</h1>
                    <p class="mt-6 text-lg leading-8 text-slate-600">{{ $producto->descripcion ?? 'Consulta las coberturas y beneficios de este paquete.' }}</p>
                    <p class="mt-8 text-3xl font-black text-blue-700">${{ number_format((float) ($producto->precio ?? 0), 2) }}</p>
                    <div class="mt-6 rounded-xl bg-white p-5 shadow-sm">
                        <span class="font-bold text-slate-700">Disponibilidad:</span>
                        <span class="ml-2 text-slate-600">{{ $producto->existencia }}</span>
                    </div>
                    <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="mt-6">
                        @csrf
                        <button class="w-full rounded-full bg-blue-700 px-6 py-3 font-bold text-white hover:bg-blue-800">Agregar al carrito y contactar</button>
                    </form>
                </div>
            </div>
        @endif
    </section>
@endsection
