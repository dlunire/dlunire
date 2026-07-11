# 02 — Instalación y primer arranque

**Licencia de este material:** AGPL-3.0-or-later.

## Requisitos

- PHP **8.2+**
- Composer 2

## Crear el proyecto

```bash
composer create-project dlunire/dlunire mi-app
cd mi-app
composer run dev
```

Al terminar `create-project`, Composer ejecuta **`bin/setup-env.php`**
(`post-create-project-cmd`):

- Si no hay `.env.type`, lo crea desde `.env.type.example`.
- Si ya existe, no lo sobrescribe.
- En terminal interactiva muestra un **spinner** de progreso.

Manual (mismo script):

```bash
composer run setup-env
# o: php bin/setup-env.php
```

Document root: **`public/`**. URL por defecto: `http://localhost:3000/`.

Equivalente al servidor:

```bash
php -S localhost:3000 -t public/
```

## Entorno mínimo (sin base de datos)

Para solo rutas/JSON/plantillas de prueba, en `.env.type` basta:

```envtype
DL_PRODUCTION: boolean = false
DL_LIFETIME: integer = 3800
```

Las variables `DL_DATABASE_*` y `DL_PREFIX` **solo hacen falta si va a usar el ORM**. Detalle: [05-entorno.md](05-entorno.md) y [08-orm.md](08-orm.md).

## Qué debería ver

- `GET /` — página de bienvenida del skeleton (HTML de referencia).
- El directorio **`.build/`** puede aparecer solo: es la caché de plantillas de DLCore. Está en `.gitignore`; se regenera sola.

## Extensión de editor

Resaltado de `.env.type`: [DL Typed Environment](https://marketplace.visualstudio.com/items?itemName=dlunire.dlunire-envtype)

## Siguiente

[03-estructura.md](03-estructura.md)
