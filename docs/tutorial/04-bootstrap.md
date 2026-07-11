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

Combina el arranque del **núcleo** (`DLCore\Boot\Project`) con el del **skeleton**:

1. **Skeleton — `SystemCredentials::load()`** — parsea `.env.type`, cookie de sesión según `DL_LIFETIME` (`HttpOnly`, `SameSite=Lax`, `Secure` si HTTPS), `session_start()`
2. **Skeleton — CORS** — `Authorizations::register_domain(cors_domains())`  
   - Dominios desde `DL_CORS_ORIGINS` (coma-separados; acepta host o URL y se queda con el host)  
   - Por defecto: `localhost`, `127.0.0.1`
3. **Skeleton — `Authorizations::init()`** — cabeceras CORS; preflight `OPTIONS`; Bearer opcional (`DL_TOKEN`)
4. **Núcleo — Constants** — `app/Constants/*.php` (vía `Path::ensure_dir` + `require_once`, como DLCore)
5. **Núcleo — Helpers** — `app/Helpers/*.php`
6. **Núcleo — Routes** — `routes/*.php` si `$autoload_routes === true` (por defecto)
7. **`DLRoute::execute()`** — despacha la petición

Firma alineada con el núcleo:

```php
Project::run();                      // carga routes/
Project::run(autoload_routes: false); // usted registra rutas a mano
```

## Detalles útiles

- **`clear_route()`** (API del skeleton) solo normaliza separadores; **no elimina puntos** del path (rutas tipo `my.app` son válidas). Sigue disponible en el bootstrap.
- Los includes del núcleo **crean el directorio** si no existe (`Path::ensure_dir`).
- Sin `.env.type` válido, el arranque puede fallar al cargar credenciales: parta de `.env.type.example` o de `bin/setup-env.php`.

## Namespaces del bootstrap

| Clase | Namespace | Archivo |
|-------|-----------|---------|
| `Project` | `Boot\` | `boot/Project.php` |
| `Authorizations` | `Boot\` | `boot/Authorizations.php` |
| `SystemCredentials` | `Framework\Auth\` | `dlunire/Auth/SystemCredentials.php` |

## Siguiente

[05-entorno.md](05-entorno.md)
