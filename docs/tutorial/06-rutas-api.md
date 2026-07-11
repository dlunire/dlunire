# 06 — Rutas HTTP y respuestas JSON

**Licencia de este material:** AGPL-3.0-or-later.

Las rutas se registran en `routes/*.php` (se cargan en el bootstrap). El skeleton
publica solo:

```php
// routes/web.php
use DLRoute\Requests\DLRoute;
use DLUnire\Controllers\WelcomeController;

DLRoute::get('/', [WelcomeController::class, 'index']);
```

El resto lo añade usted. Ejemplo de API:

```php
<?php

/**
 * @license AGPL-3.0-or-later
 */

use DLRoute\Requests\DLRoute;
use DLUnire\Controllers\WelcomeController;
use DLUnire\Controllers\HealthController;
use DLUnire\Controllers\ProductsController;

DLRoute::get('/', [WelcomeController::class, 'index']);

DLRoute::get('/api/health', [HealthController::class, 'index']);

DLRoute::get('/api/products/{id}', [ProductsController::class, 'show'])
    ->filter_by_type([
        'id' => 'integer',
    ]);
```

## Tres formas de controlador

```php
// 1) Array clase + método (recomendado)
DLRoute::get('/api/health', [HealthController::class, 'index']);

// 2) Callback
DLRoute::get('/api/ping', fn () => ['ok' => true]);

// 3) Cadena "Namespace\\Clase@metodo"
DLRoute::get('/api/ping', 'DLUnire\\Controllers\\HealthController@index');
```

## Métodos HTTP

Según DLRoute: `get`, `post`, `put`, `patch`, `delete` (y preflight `OPTIONS`
gestionado en CORS del skeleton).

## Respuestas de API

Si el handler devuelve un **array** (o estructura serializable), el stack
responde como datos (JSON en el flujo habitual de DLRoute). Eso es el núcleo del
enfoque **API first**.

Si devuelve un **string** (p. ej. `view('welcome', …)`), se trata como cuerpo
HTML/texto.

## Parámetros de URI

```php
DLRoute::get('/api/products/{id}', [ProductsController::class, 'show'])
    ->filter_by_type([
        'id' => 'integer',
    ]);
```

Más detalle de filtros y ciclo HTTP: tutorial de **DLRoute**.

## Siguiente

[07-controladores.md](07-controladores.md)
