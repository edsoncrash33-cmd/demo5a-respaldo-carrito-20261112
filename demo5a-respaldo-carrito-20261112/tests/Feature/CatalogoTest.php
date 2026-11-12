<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CatalogoTest extends TestCase
{
    public function test_informational_pages_are_available(): void
    {
        $this->get('/')->assertOk()->assertSee('Bienvenido a Aseguradora Rose')->assertSee('Catálogo');
        $this->get('/nosotros')->assertOk()->assertSee('Tu tranquilidad es nuestra misión.');
        $this->get('/contacto')->assertOk()->assertSee('Solicita tu cotización');
    }

    public function test_catalogo_renders_products_from_api(): void
    {
        Http::fake([
            'http://127.0.0.1:8001/api/public/productos' => Http::response([
                'data' => [[
                    'id' => 7,
                    'nombre' => 'Protección Familiar',
                    'descripcion' => 'Cobertura para tu familia.',
                    'precio' => '250.00',
                    'status' => 'Disponible',
                    'imagen_url' => 'https://example.test/uno.jpg',
                    'imagen_url_2' => 'https://example.test/dos.jpg',
                    'imagen_url_3' => 'https://example.test/tres.jpg',
                ]],
            ]),
        ]);

        $this->get('/catalogo')
            ->assertOk()
            ->assertSee('Catálogo de productos')
            ->assertSee('Protección Familiar')
            ->assertSee('Detalle')
            ->assertSee('Agregar');
    }

    public function test_product_detail_renders_three_images_and_availability(): void
    {
        Http::fake([
            'http://127.0.0.1:8001/api/public/productos/7' => Http::response([
                'data' => [
                    'id' => 7,
                    'nombre' => 'Protección Familiar',
                    'descripcion' => 'Cobertura para tu familia.',
                    'precio' => '250.00',
                    'status' => 'Disponible',
                    'imagen_url' => 'https://example.test/uno.jpg',
                    'imagen_url_2' => 'https://example.test/dos.jpg',
                    'imagen_url_3' => 'https://example.test/tres.jpg',
                ],
            ]),
        ]);

        $this->get('/catalogo/7')
            ->assertOk()
            ->assertSee('Detalle del producto')
            ->assertSee('uno.jpg')
            ->assertSee('dos.jpg')
            ->assertSee('tres.jpg')
            ->assertSee('Disponible');
    }

    public function test_catalogo_shows_api_errors_to_the_user(): void
    {
        Http::fake([
            'http://127.0.0.1:8001/api/public/productos' => Http::response(['message' => 'API no disponible'], 503),
        ]);

        $this->get('/catalogo')
            ->assertOk()
            ->assertSee('No pudimos cargar el catálogo');
    }

    public function test_catalogo_and_paquetes_are_separate_and_products_can_be_added_to_cart(): void
    {
        Http::fake([
            'http://127.0.0.1:8001/api/public/productos' => Http::response([
                'data' => [
                    ['id' => 1, 'nombre' => 'Seguro de Vida Rose', 'precio' => '499.00', 'status' => 'active'],
                    ['id' => 2, 'nombre' => 'Paquete Vida + Gastos Médicos', 'precio' => '1199.00', 'status' => 'active'],
                ],
            ]),
            'http://127.0.0.1:8001/api/public/productos/1' => Http::response([
                'data' => ['id' => 1, 'nombre' => 'Seguro de Vida Rose', 'precio' => '499.00', 'status' => 'active'],
            ]),
        ]);

        $this->get('/catalogo')->assertSee('Seguro de Vida Rose')->assertDontSee('Paquete Vida + Gastos Médicos');
        $this->get('/paquetes')->assertSee('Paquete Vida + Gastos Médicos')->assertDontSee('Seguro de Vida Rose');
        $this->post('/carrito/1')->assertRedirect('/contacto');
        $this->get('/contacto')->assertSee('Seguro de Vida Rose')->assertSee('Contactar por WhatsApp');
    }

    public function test_cart_can_update_remove_and_clear_items_using_session(): void
    {
        $this->withSession([
            'carrito' => [
                '1' => [
                    'id' => 1,
                    'nombre' => 'Seguro de Vida Rose',
                    'precio' => 499.00,
                    'cantidad' => 1,
                    'imagen' => 'https://example.test/vida.webp',
                ],
            ],
        ])->get('/carrito')
            ->assertOk()
            ->assertSee('Carrito de compras')
            ->assertSee('vida.webp')
            ->assertSee('Actualizar')
            ->assertSee('Vaciar carrito');

        $this->withSession(['carrito' => [
            '1' => ['id' => 1, 'nombre' => 'Seguro de Vida Rose', 'precio' => 499.00, 'cantidad' => 1],
        ]])->patch('/carrito/1', ['cantidad' => 3])
            ->assertRedirect('/carrito')
            ->assertSessionHas('carrito.1.cantidad', 3);

        $this->withSession(['carrito' => [
            '1' => ['id' => 1, 'nombre' => 'Seguro de Vida Rose', 'precio' => 499.00, 'cantidad' => 1],
        ]])->delete('/carrito/1')
            ->assertRedirect('/carrito')
            ->assertSessionMissing('carrito.1');

        $this->withSession(['carrito' => [
            '1' => ['id' => 1, 'nombre' => 'Seguro de Vida Rose', 'precio' => 499.00, 'cantidad' => 1],
        ]])->delete('/carrito')
            ->assertRedirect('/carrito')
            ->assertSessionMissing('carrito');
    }
}
