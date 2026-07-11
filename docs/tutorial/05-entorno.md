# 05 — Entorno `.env.type`

**Licencia de este material:** AGPL-3.0-or-later.

## Idea

Cada variable declara **nombre, tipo y valor**. El parser de DLCore valida tipos
(`boolean`, `string`, `integer`, `email`, `uuid`, `numeric`, `float`, …).

```envtype
DL_PRODUCTION: boolean = false
DL_LIFETIME: integer = 3800
```

Copie siempre:

```bash
cp .env.type.example .env.type
```

El archivo de ejemplo del skeleton (`.env.type.example`) es la referencia
oficial de variables documentadas.

## Mínimo (API sin base de datos)

```envtype
DL_PRODUCTION: boolean = false
DL_LIFETIME: integer = 3800
DL_CORS_ORIGINS: string = "localhost,127.0.0.1"
```

- **`DL_LIFETIME`:** segundos de vida de la cookie de sesión.
- **`DL_PRODUCTION`:** modo producción (afecta comportamiento del núcleo).

## Base de datos (ORM)

```envtype
DL_DATABASE_HOST: string = "127.0.0.1"
DL_DATABASE_PORT: integer = 3306
DL_DATABASE_USER: string = "root"
DL_DATABASE_PASSWORD: string = ""
DL_DATABASE_NAME: string = "mi_app"
DL_DATABASE_CHARSET: string = "utf8"
DL_DATABASE_COLLATION: string = "utf8_general_ci"
DL_DATABASE_DRIVE: string = "mysql"
DL_PREFIX: string = "dl_"
MULTITENANT: boolean = false
```

Notas:

| Variable | Detalle |
|----------|---------|
| **`DL_PREFIX`** | Prefijo de tablas del ORM (`Products` → `dl_products`) |
| **`DL_DATABASE_DRIVE`** | Nombre real en el núcleo (no “DRIVER”). Por defecto `mysql` |
| **`MULTITENANT`** | En desarrollo; monoinquilino: `false` ([11-en-desarrollo.md](11-en-desarrollo.md)) |
| **charset/collation** | El ejemplo del skeleton usa `utf8` / `utf8_general_ci`; puede usar `utf8mb4` si su servidor lo recomienda |

## CORS

Hosts **sin** esquema, separados por comas:

```envtype
DL_CORS_ORIGINS: string = "localhost,127.0.0.1,app.ejemplo.com"
```

Si se omite, el bootstrap permite `localhost` y `127.0.0.1`.

## Opcional

| Grupo | Variables |
|-------|-----------|
| Correo | `MAIL_HOST`, `MAIL_USERNAME` (tipo `email`), `MAIL_PASSWORD`, `MAIL_PORT`, `MAIL_COMPANY_NAME`, `MAIL_CONTACT` |
| reCAPTCHA | `G_SITE_KEY`, `G_SECRET_KEY` |
| API Bearer | `DL_TOKEN` — si está definido, orígenes CORS pueden exigir `Authorization: Bearer …` |

## Siguiente

[06-rutas-api.md](06-rutas-api.md)
