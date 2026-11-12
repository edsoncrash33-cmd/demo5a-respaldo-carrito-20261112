@extends('layouts.app')

@section('title', ($esPaquetes ? 'Paquetes combinados' : 'Catálogo de productos').' | Aseguradora Rose')

@section('content')
    <section class="mx-auto max-w-7xl px-6 py-12">
        <div class="mb-10">
            <p class="section-kicker">{{ $esPaquetes ? 'Paquetes combinados' : 'Catálogo de productos' }}</p>
            <h1 class="mt-2 text-4xl font-black text-blue-950">{{ $esPaquetes ? 'Protección que combina lo mejor para ti' : 'Seguros para cada necesidad' }}</h1>
        </div>
        @if($error)
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-red-800" role="alert">
                <p class="font-bold">No pudimos cargar el catálogo.</p>
                <p class="mt-1">{{ $error }}</p>
            </div>
        @elseif(count($productos) === 0)
            <p class="rounded-2xl bg-white p-8 text-center text-slate-600 shadow-sm">No hay opciones disponibles.</p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($productos as $producto)
                    @php($imagen = $producto->imagenes[0] ?? null)
                    <article class="overflow-hidden rounded-2xl bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="image-frame flex h-56 items-center justify-center bg-blue-50">
                            @if($imagen)
                                <span class="image-skeleton" aria-hidden="true"></span>
                                <img src="{{ $imagen }}" alt="{{ $producto->nombre ?? 'Producto' }}" loading="lazy" class="image-loading h-full w-full object-cover" onload="this.classList.add('is-loaded')" onerror="this.previousElementSibling.remove(); this.remove()">
                            @else
                                <div class="image-placeholder"><span aria-hidden="true">♢</span><small>Imagen del paquete</small></div>
                            @endif
                        </div>
                        <div class="p-6">
                            @if(str_starts_with($producto->nombre ?? '', 'Paquete'))
                                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-800">Paquete combinado</span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">Seguro individual</span>
                            @endif
                            <h2 class="text-xl font-bold text-blue-950">{{ $producto->nombre ?? 'Paquete sin nombre' }}</h2>
                            <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $producto->descripcion ?? 'Conoce las coberturas incluidas en este paquete.' }}</p>
                            <div class="mt-5 flex items-center justify-between gap-3">
                                <span class="text-lg font-black text-blue-700">${{ number_format((float) ($producto->precio ?? 0), 2) }}</span>
                                <div class="flex gap-2">
                                    <a href="{{ route('catalogo.detalle', $producto->id) }}" class="rounded-full border border-blue-700 px-4 py-2 text-sm font-bold text-blue-700 hover:bg-blue-50">Detalle</a>
                                    <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                                        @csrf
                                        <button class="rounded-full bg-blue-700 px-4 py-2 text-sm font-bold text-white hover:bg-blue-800">Agregar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
