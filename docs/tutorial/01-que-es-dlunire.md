# 01 — Qué es DLUnire y API first

**Licencia de este material:** AGPL-3.0-or-later.

## En una frase

**DLUnire** es un framework PHP **orientado a API**: usted escribe controladores y
rutas que devuelven datos (arrays → JSON); el HTML es opcional.

## Piezas del ecosistema

| Pieza | Paquete | Rol |
|-------|---------|-----|
| **Skeleton** | `dlunire/dlunire` | Su aplicación: `app/`, `routes/`, `resources/`, `public/` |
| **DLCore** | `dlunire/dlcore` | Kernel: ORM, plantillas, `.env.type`, correo |
| **DLRoute** | `dlunire/dlroute` | HTTP: rutas, peticiones, document root, uploads |
| **DLStorage** | `dlunire/dlstorage` | Almacenamiento binario y soporte a credenciales cifradas |

`composer create-project dlunire/dlunire` instala el skeleton y declara
`dlunire/dlcore` (`^2.1` en el estado actual del skeleton). DLRoute y DLStorage
entran como dependencias transitivas de DLCore.

## API first

- **Primario:** endpoints HTTP, JSON, ORM, validación de entrada, CORS.
- **Opcional:** plantillas `*.template.html` (la bienvenida del skeleton es un ejemplo).
- Un controlador puede devolver un `array`; el stack lo serializa como respuesta de datos.
- Un `string` (p. ej. `view(...)`) se trata como cuerpo HTML/texto.

## Capas

```
Su código (app/, routes/)
        ↓
Capa skeleton (dlunire/, boot/)
        ↓
DLCore (kernel) + DLRoute (HTTP) + DLStorage
```

Cada paquete tiene responsabilidad clara. El detalle del kernel está en el
tutorial de **DLCore**; este tutorial cubre el **esqueleto y el flujo de una app API**.

## Licencia

Código y documentación del skeleton: **AGPL-3.0-or-later**. Ver
[12-licencia.md](12-licencia.md).

## Siguiente

[02-instalacion.md](02-instalacion.md)
