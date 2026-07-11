# 08 — Modelos y ORM

**Licencia de este material:** AGPL-3.0-or-later.

El ORM vive en **DLCore** (`DLCore\Database\Model`). El skeleton solo define
sus modelos en `app/Models/`.

## Modelo vacío

```php
<?php

/**
 * @license AGPL-3.0-or-later
 */

namespace DLUnire\Models;

use DLCore\Database\Model;

final class Products extends Model {
    // Con DL_PREFIX = "dl_" → tabla dl_products
}
```

Tabla (o vista / SQL) forzada:

```php
// Nombre de tabla
protected static ?string $table = 'catalog_products';

// O origen personalizado (vista / subconsulta), según el kernel:
// protected static ?string $table = "SELECT * FROM dl_employee WHERE status = 1";
```

## Lectura: `get()`, `all()`, `paginate()`

| Método | Tope | Uso |
|--------|------|-----|
| **`get()`** | **Sí** — `DLDatabase::DEFAULT_GET_LIMIT` (**1000**) si no hay `limit()` | Lecturas acotadas; **no** es “toda la tabla” |
| **`limit(n)`** | El suyo | Control explícito en el builder |
| **`paginate($page, $rows)`** | Páginas (`$rows` por defecto 100 en el kernel) | **Listados de API** (recomendado) |
| **`all()`** | **Ninguno** | A **riesgo del programador**; puede ser masivo |

**Motivo del tope en `get()`:** evitar colgar el servidor con tablas enormes.
Es intencional del núcleo.

```php
use DLUnire\Models\Products;
use DLCore\Database\Model;

Products::create([
    'product_name' => 'Teclado mecánico',
    'price' => 189000,
]);

// Hasta ~1000 filas (tope de seguridad)
$rows = Products::get();

// Filtrar (mismo tope en ->get() si no hay limit())
$items = Products::where('price', '>', '100000')
    ->where('product_name', 'LIKE', '%teclado%', Model::AND)
    ->get();

// Listado API
$page = Products::paginate(page: 1, rows: 20);

// Sin tope — solo si el conjunto es acotado a propósito
// $all = Products::all();
```

## Operadores lógicos

`Model::AND` y `Model::OR` para encadenar condiciones en `where(...)`.

## `.env.type` para ORM

```envtype
DL_DATABASE_HOST: string = "127.0.0.1"
DL_DATABASE_PORT: integer = 3306
DL_DATABASE_USER: string = "root"
DL_DATABASE_PASSWORD: string = ""
DL_DATABASE_NAME: string = "mi_app"
DL_DATABASE_DRIVE: string = "mysql"
DL_PREFIX: string = "dl_"
MULTITENANT: boolean = false
```

Cree la base y la tabla (p. ej. `dl_products`) en MySQL antes de probar.

## Modelo `Users` del skeleton

`DLUnire\Models\Users` extiende `Framework\Auth\UserBase` (camino de auth
básica del skeleton). Está pensado para demos de DLAuth; **no** es el modelo
canónico de una API de producción. Ver [11-en-desarrollo.md](11-en-desarrollo.md).

## Más profundidad

Tutorial DLCore: [03-modelos-orm](https://github.com/dlunire/dlcore/blob/master/docs/tutorial/03-modelos-orm.md),
agregaciones, transacciones, vistas SQL.

> **Nota:** la actualización de la versión de **dlstorage** en el núcleo
> (DLCore) la gestiona el mantenedor del proyecto; este tutorial del skeleton
> no la modifica.

## Siguiente

[09-helpers.md](09-helpers.md)
