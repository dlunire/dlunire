# 09 — Helpers del skeleton

**Licencia de este material:** AGPL-3.0-or-later.

Todo `app/Helpers/*.php` se incluye en el bootstrap (`Project::run()`). Defina
funciones con `if (!function_exists(...))` para no chocar con reincludes.

## Esenciales

| Helper | Archivo | Uso |
|--------|---------|-----|
| `view($name, $vars)` | `functions.php` | Render de plantilla DLCore |
| `view_pdf(...)` | `functions.php` | HTML → PDF (Dompdf) |
| `route($uri)` | `routes.php` | URL relativa al proyecto (subdirectorios) |
| `asset($uri)` | `routes.php` | URL de recurso bajo `public/` |
| `get_token()` | `security.php` | Token CSRF de entorno/sesión |
| `validate_ref(...)` | `security.php` | Validar referencia/token CSRF |
| `get_sitekey()` | `security.php` | reCAPTCHA (si hay `G_SITE_KEY`) |
| `js(...)`, `css(...)`, … | `resources.php` | Inclusión de assets en plantillas |
| Formato de moneda | `currency.php` | Presentación ES/EN |

## Plantillas

- Archivos: `resources/**/*.template.html`
- Includes: `@includes('layouts.icons.isotipo')`
- Variables: `{{ $nombre }}` y directivas del motor DLCore
- Caché compilada: **`.build/`** (no versionar; se regenera)

En una API pura casi no usará plantillas; siguen disponibles para paneles, emails o PDFs.

## Constants

`app/Constants/*.php` también se carga en el bootstrap (después de Helpers).
Use para constantes de dominio de su aplicación.

## Siguiente

[10-cors-sesion.md](10-cors-sesion.md)
