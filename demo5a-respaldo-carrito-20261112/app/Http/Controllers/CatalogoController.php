<?php

namespace App\Http\Controllers;

use App\Services\ProductosApi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CatalogoController extends Controller
{
    public function __construct(private readonly ProductosApi $api) {}

    public function index(string $tipo = 'individuales'): View
    {
        try {
            $productos = $this->api->listar();
            $error = null;
        } catch (Throwable $exception) {
            report($exception);
            $productos = [];
            $error = 'No pudimos cargar el catálogo en este momento. Intenta nuevamente más tarde.';
        }

        $esPaquetes = $tipo === 'paquetes';
        $productos = array_values(array_filter($productos, static function (object $producto) use ($esPaquetes): bool {
            $esPaquete = str_starts_with((string) ($producto->nombre ?? ''), 'Paquete');

            return $esPaquetes ? $esPaquete : ! $esPaquete;
        }));

        return view('catalogo.index', compact('productos', 'error', 'esPaquetes'));
    }

    public function show(string $id): View
    {
        try {
            $producto = $this->api->obtener($id);
            $error = null;
        } catch (Throwable $exception) {
            report($exception);
            $producto = null;
            $error = 'No pudimos cargar este paquete en este momento. Intenta nuevamente más tarde.';
        }

        return view('catalogo.show', compact('producto', 'error'));
    }

    public function addToCart(Request $request, string $id): RedirectResponse
    {
        try {
            $producto = $this->api->obtener($id);
            $carrito = $request->session()->get('carrito', []);
            $key = (string) $producto->id;

            if (isset($carrito[$key])) {
                $carrito[$key]['cantidad']++;
            } else {
                $carrito[$key] = [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre ?? 'Producto',
                    'precio' => (float) ($producto->precio ?? 0),
                    'imagen' => $producto->imagenes[0] ?? null,
                    'cantidad' => 1,
                ];
            }

            $request->session()->put('carrito', $carrito);

            return redirect()->route('contacto')->with('success', 'Producto agregado a tu solicitud.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'No pudimos agregar este producto. Intenta nuevamente.');
        }
    }

    public function cart(Request $request): View
    {
        return view('carrito', [
            'carrito' => $request->session()->get('carrito', []),
        ]);
    }

    public function updateCart(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1', 'max:99'],
        ]);
        $carrito = $request->session()->get('carrito', []);
        $key = (string) $id;

        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] = $validated['cantidad'];
            $request->session()->put('carrito', $carrito);
        }

        return redirect()->route('carrito')->with('success', 'Cantidad actualizada.');
    }

    public function removeFromCart(Request $request, string $id): RedirectResponse
    {
        $carrito = $request->session()->get('carrito', []);
        unset($carrito[(string) $id]);
        $request->session()->put('carrito', $carrito);

        return redirect()->route('carrito');
    }

    public function clearCart(Request $request): RedirectResponse
    {
        $request->session()->forget('carrito');

        return redirect()->route('carrito')->with('success', 'Carrito vaciado.');
    }
}
