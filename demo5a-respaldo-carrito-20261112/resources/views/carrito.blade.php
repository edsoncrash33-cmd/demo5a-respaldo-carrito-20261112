@extends('layouts.app')

@section('title', 'Carrito de compras | Aseguradora Rose')

@section('content')
    @php
        $total = collect($carrito)->sum(fn ($item) => (float) $item['precio'] * (int) $item['cantidad']);
    @endphp
    <section class="mx-auto max-w-5xl px-6 py-12">
        <p class="section-kicker">Carrito de compras</p>
        <h1 class="mt-3 text-4xl font-black text-blue-950">Tu selección de seguros</h1>

        @if(session('success'))
            <div class="mt-6 rounded-xl bg-green-50 p-4 text-green-800" role="status">{{ session('success') }}</div>
        @endif

        @forelse($carrito as $item)
            <article class="mt-6 grid gap-5 rounded-2xl bg-white p-5 shadow-sm sm:grid-cols-[8rem_1fr_auto] sm:items-center">
                @if(!empty($item['imagen']))
                    <img src="{{ $item['imagen'] }}" alt="{{ $item['nombre'] }}" class="h-28 w-full rounded-xl object-cover">
                @else
                    <div class="image-placeholder image-placeholder-large h-28 min-h-0"><span aria-hidden="true">♢</span><small>Sin imagen</small></div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-blue-950">{{ $item['nombre'] }}</h2>
                    <p class="mt-1 text-slate-600">Precio: ${{ number_format((float) $item['precio'], 2) }}</p>
                    <p class="mt-1 font-bold text-blue-700">Subtotal: ${{ number_format((float) $item['precio'] * (int) $item['cantidad'], 2) }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 sm:justify-end">
                    <form action="{{ route('carrito.actualizar', $item['id']) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <label for="cantidad-{{ $item['id'] }}" class="text-sm font-bold text-slate-700">Cantidad</label>
                        <input id="cantidad-{{ $item['id'] }}" name="cantidad" type="number" min="1" max="99" value="{{ $item['cantidad'] }}" class="w-20 rounded-lg border border-slate-300 px-3 py-2">
                        <button class="rounded-full bg-blue-700 px-4 py-2 text-sm font-bold text-white hover:bg-blue-800">Actualizar</button>
                    </form>
                    <form action="{{ route('carrito.eliminar', $item['id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-full border border-red-200 px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50">Eliminar</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="mt-8 rounded-2xl bg-white p-10 text-center shadow-sm">
                <p class="text-slate-600">Tu carrito está vacío.</p>
                <a href="{{ route('catalogo') }}" class="mt-5 inline-flex rounded-full bg-blue-700 px-6 py-3 font-bold text-white hover:bg-blue-800">Explorar catálogo</a>
            </div>
        @endforelse

        @if(count($carrito) > 0)
            <div class="mt-8 flex flex-col items-end gap-4">
                <p class="text-2xl font-black text-blue-700">Total estimado: ${{ number_format($total, 2) }}</p>
                <div class="flex flex-wrap justify-end gap-3">
                    <form action="{{ route('carrito.vaciar') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-full border border-red-200 px-6 py-3 font-bold text-red-600 hover:bg-red-50">Vaciar carrito</button>
                    </form>
                    <a href="{{ route('contacto') }}" class="rounded-full bg-green-600 px-6 py-3 font-bold text-white hover:bg-green-700">Contactar con mi selección</a>
                </div>
            </div>
        @endif
    </section>
@endsection
