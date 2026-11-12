# Aseguradora Rose

Sitio web público desarrollado con Laravel para presentar servicios y paquetes de seguros. El catálogo se obtiene desde una API REST mediante Laravel HttpClient; este proyecto no consulta ni guarda información en una base de datos local.

## Requisitos

- PHP 8.2+
- Composer
- Node.js y npm
- API de seguros disponible en `http://127.0.0.1:8001`

## Instalación

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
npm run build
```

En `.env`, `PRODUCTOS_API_URL` apunta al endpoint público de paquetes:

```dotenv
PRODUCTOS_API_URL=http://127.0.0.1:8001/api/productos
```

La respuesta debe entregar `nombre`, `descripcion`, `precio`, `status` o `existencia`, además de `imagen_url`, `imagen_url_2` e `imagen_url_3` con URLs públicas. Las imágenes no se guardan en este proyecto cliente; se administran desde la API.

## Conectar con Postman

1. Levanta el proyecto API en `C:\xampp\htdocs\API_REPOSTERIA` en el puerto 8001:

   ```powershell
   php artisan serve --host=127.0.0.1 --port=8001
   ```

2. En Postman prueba:

   - `GET http://127.0.0.1:8001/api/public/productos`
   - `GET http://127.0.0.1:8001/api/public/productos/1`

3. Para cada paquete registra las tres URLs públicas en `imagen_url`, `imagen_url_2` e `imagen_url_3`. El cliente no necesita token para consultar esos dos endpoints públicos.

Para cargar el catálogo inicial de Aseguradora Rose en la API, ejecuta una sola vez:

```powershell
php artisan db:seed --class=SegurosRoseSeeder
```

Se crearán los seguros de Vida, Auto, Estudiantil y Gastos Médicos, además de los paquetes combinados Vida + Gastos Médicos y Auto + Estudiantil. Las tres columnas de imagen quedan vacías intencionalmente para que las completes desde Postman.

## Ejecución del sitio

```powershell
php artisan serve --host=127.0.0.1 --port=8095
```

Abre `http://127.0.0.1:8095`. Las páginas son `/`, `/nosotros`, `/catalogo`, `/catalogo/{id}` y `/contacto`.

`/catalogo` muestra los seguros individuales y `/paquetes` muestra únicamente los paquetes combinados. Desde cualquiera de las dos páginas puedes agregar una opción al carrito; `/contacto` conserva la selección en sesión y genera el mensaje para WhatsApp. Cambia el número `525500000000` en `resources/views/contacto.blade.php` por el celular real de Aseguradora Rose.

## Verificación

```powershell
php artisan test
npm run build
```
