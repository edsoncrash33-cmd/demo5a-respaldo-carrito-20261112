<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ProductosApi
{
    public function listar(): array
    {
        $response = $this->request()->get($this->url());
        $this->ensureSuccessful($response, 'No se pudo cargar el catálogo.');

        $payload = $response->json();
        $items = is_array($payload) && array_key_exists('data', $payload)
            ? $payload['data']
            : $payload;

        if (! is_array($items)) {
            throw new RuntimeException('La API devolvió un catálogo inválido.');
        }

        return array_map(fn (mixed $item): object => $this->normalize($item), $items);
    }

    public function obtener(string $id): object
    {
        if (! ctype_digit($id) || (int) $id < 1) {
            throw new RuntimeException('El identificador del producto no es válido.');
        }

        $response = $this->request()->get($this->url('/'.rawurlencode($id)));
        $this->ensureSuccessful($response, 'No se pudo cargar el producto.');

        $payload = $response->json();
        $item = is_array($payload) && array_key_exists('data', $payload)
            ? $payload['data']
            : $payload;

        if (! is_array($item)) {
            throw new RuntimeException('La API devolvió un producto inválido.');
        }

        return $this->normalize($item);
    }

    private function normalize(mixed $item): object
    {
        if (! is_array($item)) {
            throw new RuntimeException('La API devolvió un producto con formato inválido.');
        }

        $item['imagenes'] = array_values(array_filter([
            $item['imagen_url'] ?? null,
            $item['imagen_url_2'] ?? null,
            $item['imagen_url_3'] ?? null,
        ], static fn (mixed $url): bool => is_string($url) && $url !== ''));

        $item['existencia'] = $item['existencia']
            ?? $item['stock']
            ?? match ($item['status'] ?? null) {
                'active' => 'Disponible',
                'inactive' => 'No disponible',
                default => $item['status'] ?? 'No especificada',
            };

        return (object) $item;
    }

    private function request(): PendingRequest
    {
        return Http::acceptJson()
            ->timeout(10)
            ->retry(2, 200);
    }

    private function url(string $suffix = ''): string
    {
        return rtrim((string) config('services.productos_api.url'), '/').$suffix;
    }

    private function ensureSuccessful(Response $response, string $fallback): void
    {
        if ($response->successful()) {
            return;
        }

        $message = $response->json('message');
        throw new RuntimeException(is_string($message) ? $message : $fallback);
    }
}
