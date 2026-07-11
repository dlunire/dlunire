# Tutorial de uso — DLUnire (skeleton)

**Licencia de este material:** AGPL-3.0-or-later.

Guía progresiva del **skeleton** `dlunire/dlunire`: framework PHP **orientado a API**.
Cada capítulo es independiente; se recomienda el orden indicado.

| # | Tema | Archivo |
|---|------|---------|
| 1 | Qué es DLUnire y API first | [01-que-es-dlunire.md](01-que-es-dlunire.md) |
| 2 | Instalación y primer arranque | [02-instalacion.md](02-instalacion.md) |
| 3 | Estructura del proyecto | [03-estructura.md](03-estructura.md) |
| 4 | Bootstrap: `Project::run()` | [04-bootstrap.md](04-bootstrap.md) |
| 5 | Entorno `.env.type` | [05-entorno.md](05-entorno.md) |
| 6 | Rutas HTTP y respuestas JSON | [06-rutas-api.md](06-rutas-api.md) |
| 7 | Controladores y entrada tipada | [07-controladores.md](07-controladores.md) |
| 8 | Modelos y ORM (`get` / `all` / `paginate`) | [08-orm.md](08-orm.md) |
| 9 | Helpers del skeleton | [09-helpers.md](09-helpers.md) |
| 10 | CORS, sesión y operación | [10-cors-sesion.md](10-cors-sesion.md) |
| 11 | En desarrollo y límites | [11-en-desarrollo.md](11-en-desarrollo.md) |
| 12 | Licencia AGPL y tienda | [12-licencia.md](12-licencia.md) |

## Convención de nombres

- **snake_case** en métodos, funciones, variables y claves de array de aplicación.
- **PascalCase** en clases (`WelcomeController`, `Products`), PSR-4: `DLUnire\…`, `Framework\…`, `Boot\…`.

## Requisitos

- PHP **≥ 8.2**
- Composer 2
- Dependencias del skeleton: `dlunire/dlcore` (trae **DLRoute** y **DLStorage**)

## Tutoriales del ecosistema

| Paquete | Rol | Tutorial |
|---------|-----|----------|
| **DLCore** | Kernel (ORM, vistas, env, correo) | [github.com/dlunire/dlcore/docs/tutorial](https://github.com/dlunire/dlcore/blob/master/docs/tutorial/README.md) |
| **DLRoute** | HTTP, rutas, peticiones | [github.com/dlunire/dlroute/docs/tutorial](https://github.com/dlunire/dlroute/blob/master/docs/tutorial/README.md) |
| **DLStorage** | Binario / credenciales | [github.com/dlunire/dlstorage](https://github.com/dlunire/dlstorage) |

Este tutorial cubre el **esqueleto y el flujo de una app API**. El detalle profundo del kernel está en DLCore.

> La actualización de la versión de **dlstorage** en **DLCore** la realiza el
> mantenedor del núcleo; este repositorio skeleton no la cambia.

## Licencia

Todo el código y la documentación de este skeleton se distribuyen bajo **AGPL-3.0-or-later**.
Texto: [`LICENSE`](../../LICENSE) · [SPDX](https://spdx.org/licenses/AGPL-3.0-or-later.html).

Comercial (despliegue cerrado): [store.dlunire.dev](https://store.dlunire.dev/).
