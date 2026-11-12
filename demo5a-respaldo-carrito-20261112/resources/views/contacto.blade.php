@extends('layouts.app')

@section('title', 'Contacto | Aseguradora Rose')

@section('content')
    @php
        $carrito = session('carrito', []);
        $total = collect($carrito)->sum(fn ($item) => $item['precio'] * $item['cantidad']);
        $mensaje = "Hola, quiero información sobre:%0A".collect($carrito)->map(fn ($item) => "- {$item['nombre']} (cantidad: {$item['cantidad']})")->implode('%0A');
    @endphp
    <section class="mx-auto max-w-4xl px-6 py-16">
        <p class="section-kicker">Contacto</p>
        <h1 class="mt-3 text-4xl font-black text-blue-950">Solicita tu cotización</h1>
        @if(session('success')) <div class="mt-6 rounded-xl bg-green-50 p-4 text-green-800">{{ session('success') }}</div> @endif
        <div class="mt-8 grid gap-8 lg:grid-cols-2">
            <div class="rounded-2xl bg-white p-8 shadow-sm">
                <h2 class="text-2xl font-black text-blue-950">Tu carrito</h2>
                @forelse($carrito as $item)
                    <div class="mt-5 flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div><p class="font-bold">{{ $item['nombre'] }}</p><p class="text-sm text-slate-500">{{ $item['cantidad'] }} x ${{ number_format($item['precio'], 2) }}</p></div>
                        <form action="{{ route('carrito.eliminar', $item['id']) }}" method="POST">@csrf @method('DELETE')<button class="text-sm font-bold text-red-600">Quitar</button></form>
                    </div>
                @empty
                    <p class="mt-5 text-slate-600">Aún no agregas una cobertura. Explora el catálogo para comenzar.</p>
                @endforelse
                <p class="mt-6 text-right text-xl font-black text-blue-700">Total estimado: ${{ number_format($total, 2) }}</p>
            </div>
            <div class="rounded-2xl bg-white p-8 shadow-sm">
                <h2 class="text-2xl font-black text-blue-950">Habla con un asesor</h2>
                <p class="mt-3 text-slate-600">Envíanos tu selección por WhatsApp y te ayudaremos a cotizarla.</p>
                <a href="https://wa.me/525500000000?text={{ $mensaje }}" target="_blank" rel="noopener" class="mt-6 inline-flex w-full justify-center rounded-full bg-green-600 px-6 py-3 font-bold text-white hover:bg-green-700">Contactar por WhatsApp</a>
                <p class="mt-5 text-sm text-slate-500">También puedes llamarnos al <strong>55 0000 0000</strong>.</p>
            </div>
        </div>
    </section>
@endsection
