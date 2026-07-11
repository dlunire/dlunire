# 07 — Controladores y entrada tipada

**Licencia de este material:** AGPL-3.0-or-later.

## Jerarquía (estado actual)

```
DLRoute\Config\Controller          ← base HTTP (DLRoute)
        ↑
        ├── Framework\Config\Controller   ← skeleton (get_email, CSRF cookie, …)
        └── DLCore\Core\BaseController    ← kernel (alternativa del núcleo)
```

En el skeleton, lo habitual es:

```php
use Framework\Config\Controller;

final class ProductsController extends Controller {
    // …
}
```

También puede extender **`DLCore\Core\BaseController`** (API del kernel) o
**`DLRoute\Config\Controller`** (mínimo). Ambas ramas parten de DLRoute; no son
la misma clase.

`WelcomeController` del skeleton extiende `Framework\Config\Controller`.

## Ejemplo API

```php
<?php

/**
 * @license AGPL-3.0-or-later
 */

namespace DLUnire\Controllers;

use Framework\Config\Controller;
use DLUnire\Models\Products;

final class ProductsController extends Controller {

    public function index(): array {
        $page = $this->get_integer('page');

        return [
            'items' => Products::paginate(page: $page, rows: 20),
        ];
    }

    public function store(): array {
        $name = $this->get_required('product_name');
        $price = $this->get_numeric('price');

        Products::create([
            'product_name' => $name,
            'price' => $price,
        ]);

        return ['ok' => true];
    }
}
```

```php
use DLRoute\Requests\DLRoute;
use DLUnire\Controllers\ProductsController;

DLRoute::get('/api/products', [ProductsController::class, 'index']);
DLRoute::post('/api/products', [ProductsController::class, 'store']);
```

Prueba: `http://localhost:3000/api/products?page=1`

## Lectura tipada de la petición

Use los helpers del controlador (no `$_GET` / `$_POST` a mano):

| Método | Uso |
|--------|-----|
| `get_integer('campo')` | Entero |
| `get_float('campo')` | Flotante |
| `get_numeric('campo')` | Número (int o float) |
| `get_string('campo')` | Cadena |
| `get_email('campo')` | Email validado |
| `get_uuid('campo')` | UUID |
| `get_password('campo')` | Contraseña validada |
| `get_boolean('campo')` | Booleano |
| `get_required('campo')` | Obligatorio |
| `get_input('campo')` | Opcional (`null` si falta) |
| `get_values()` | Array asociativo del cuerpo/params |
| `get_content()` | Cuerpo en bruto (texto) |

Detalle y validación avanzada: tutorial **DLCore** (capítulo de controladores).

## CSRF en el skeleton

`Framework\Config\Controller` envía cookie `__csrf` al construir y expone
`validate_csrf_token()` para mutaciones sensibles. En una API JSON con tokens
Bearer / JWT puede no usarlo; en formularios del mismo origen sí.

## Bienvenida HTML (opcional)

`WelcomeController` devuelve `view('welcome', …)`: demo de plantillas del
skeleton, no el camino principal de una API.

## Siguiente

[08-orm.md](08-orm.md)
