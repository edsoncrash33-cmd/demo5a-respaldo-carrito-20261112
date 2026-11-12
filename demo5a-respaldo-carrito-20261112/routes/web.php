<?php

use App\Http\Controllers\CatalogoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'inicio')->name('inicio');
Route::view('/nosotros', 'nosotros')->name('nosotros');
Route::get('/catalogo', [CatalogoController::class, 'index'])->defaults('tipo', 'individuales')->name('catalogo');
Route::get('/paquetes', [CatalogoController::class, 'index'])->defaults('tipo', 'paquetes')->name('paquetes');
Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->whereNumber('id')->name('catalogo.detalle');
Route::post('/carrito/{id}', [CatalogoController::class, 'addToCart'])->whereNumber('id')->name('carrito.agregar');
Route::get('/carrito', [CatalogoController::class, 'cart'])->name('carrito');
Route::patch('/carrito/{id}', [CatalogoController::class, 'updateCart'])->whereNumber('id')->name('carrito.actualizar');
Route::delete('/carrito/{id}', [CatalogoController::class, 'removeFromCart'])->whereNumber('id')->name('carrito.eliminar');
Route::delete('/carrito', [CatalogoController::class, 'clearCart'])->name('carrito.vaciar');
Route::view('/contacto', 'contacto')->name('contacto');
