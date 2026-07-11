# 04 — Bootstrap: `Project::run()`

**Licencia de este material:** AGPL-3.0-or-later.

## Entrada

`public/index.php` (document root):

```php
include dirname(__DIR__) . "/vendor/autoload.php";
set_exception_handler([DLExceptionHandler::class, 'handle']);
Project::run();
```

1. Carga el autoload de Composer  
2. Registra el manejador de excepciones del skeleton  
3. Llama a `Boot\Project::run()`

## Orden de arranque (`Boot\Project`)

1. **`SystemCredentials::load()`** — parsea `.env.type`, configura cookie de sesión según `DL_LIFETIME` (`HttpOnly`, `SameSite=Lax`, `Secure` si HTTPS), `session_start()`
2. **CORS** — `Authorizations::register_domain(cors_domains())`  
   - Dominios desde `DL_CORS_ORIGINS` (coma-separados; acepta host o URL y se queda con el host)  
   - Por defecto: `localhost`, `127.0.0.1`
3. **`Authorizations::init()`** — cabeceras CORS; preflight `OPTIONS`; validación opcional de `DL_TOKEN` (Bearer)
4. Incluye **`app/Helpers/*.php`**
5. Incluye **`app/Constants/*.php`**
6. Incluye **`routes/*.php`** (p. ej. `routes/web.php`)
7. **`DLRoute::execute()`** — despacha la petición

## Detalles útiles

- **`clear_route()`** solo normaliza separadores de ruta; **no elimina puntos** del path (rutas tipo `my.app` son válidas).
- Cualquier `*.php` en `app/Helpers/` se carga siempre: ponga ahí solo helpers globales.
- Las rutas se registran al incluir `routes/web.php`; no hace falta un `require` manual extra.
- Sin `.env.type` válido, el arranque puede fallar al cargar credenciales: parta de `.env.type.example`.

## Namespaces del bootstrap

| Clase | Namespace | Archivo |
|-------|-----------|---------|
| `Project` | `Boot\` | `boot/Project.php` |
| `Authorizations` | `Boot\` | `boot/Authorizations.php` |
| `SystemCredentials` | `Framework\Auth\` | `dlunire/Auth/SystemCredentials.php` |

## Siguiente

[05-entorno.md](05-entorno.md)
