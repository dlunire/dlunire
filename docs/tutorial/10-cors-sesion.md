# 10 — CORS, sesión y operación

**Licencia de este material:** AGPL-3.0-or-later.

## CORS

Configuración en `.env.type`:

```envtype
DL_CORS_ORIGINS: string = "localhost,127.0.0.1,app.ejemplo.com"
```

- Lista de **hosts** (sin `https://`), separados por comas.
- El bootstrap acepta host o URL completa y registra solo el host.
- Por defecto: `localhost` y `127.0.0.1`.
- Implementación: `Boot\Project::cors_domains()` + `Boot\Authorizations`.

Cabeceras al permitir un origen:

- `Access-Control-Allow-Origin: <origen>`
- `Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, Authorization`
- `Access-Control-Allow-Credentials: true`

Preflight `OPTIONS` → 200 y fin de petición.

### `DL_TOKEN` (opcional)

Si define `DL_TOKEN` en el entorno y la petición trae `Origin` permitido,
puede exigirse:

```http
Authorization: Bearer <mismo-valor-que-DL_TOKEN>
```

Si el token de entorno está vacío, no se exige Bearer. Ver
`Authorizations::validate_token`.

## Sesión

- Se inicia en `Framework\Auth\SystemCredentials::load()`.
- Vida de cookie alineada con **`DL_LIFETIME`** (segundos).
- Opciones: `HttpOnly`, `SameSite=Lax`, `Secure` si hay HTTPS.
- Validaciones internas de tiempo/origen de sesión del skeleton (capa auth básica).

## Producción (checklist mínimo)

1. `DL_PRODUCTION: boolean = true`
2. `DL_CORS_ORIGINS` con sus dominios reales
3. Credenciales de BD y secretos **solo** en `.env.type` (no versionado)
4. Document root = **`public/`** (nunca la raíz del proyecto)
5. HTTPS y permisos de escritura solo donde haga falta (p. ej. `.build/` si usa vistas)
6. No confiar en DLAuth para producción ([11-en-desarrollo.md](11-en-desarrollo.md))

## Siguiente

[11-en-desarrollo.md](11-en-desarrollo.md)
