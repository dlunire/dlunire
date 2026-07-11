# 12 — Licencia AGPL y tienda

**Licencia de este material:** AGPL-3.0-or-later.

## Código del skeleton y del ecosistema

El skeleton **DLUnire** se distribuye bajo **AGPL-3.0-or-later**.

- Texto completo: [`LICENSE`](../../LICENSE) en la raíz del proyecto
- Identificador SPDX: [AGPL-3.0-or-later](https://spdx.org/licenses/AGPL-3.0-or-later.html)
- Campo Composer: `"license": "AGPL-3.0-or-later"`

Los archivos PHP y la documentación del skeleton deben declarar:

```text
@license AGPL-3.0-or-later
```

o el encabezado AGPL estándar / la línea **Licencia de este material:** en Markdown.

## Qué implica (resumen práctico)

- Puede **desarrollar**, aprender y desplegar bajo AGPL.
- Si terceros usan su app por red y el código de **su aplicación** no será público
  bajo AGPL, necesita **permiso de despliegue cerrado** (licencia comercial) o
  publicar fuentes.

Esto **no** es “comprar el framework para empezar a programar”: programar es libre.

## Tienda

Oferta comercial (tarifas, despliegue cerrado):

- [store.dlunire.dev](https://store.dlunire.dev/)

La bienvenida del skeleton solo **enlaza** a la tienda; no sirve la tienda en local.

## Dependencias

| Paquete | Notas |
|---------|--------|
| `dlunire/dlcore` | Kernel; su licencia y versión se gestionan en el repositorio del núcleo |
| `dlunire/dlroute` | HTTP (transitiva) |
| `dlunire/dlstorage` | Binario/credenciales (transitiva). **La actualización de versión en DLCore la hace el mantenedor del núcleo**, no este skeleton |

## Tutoriales relacionados

| Tema | Dónde |
|------|--------|
| Kernel en profundidad | [DLCore tutorial](https://github.com/dlunire/dlcore/blob/master/docs/tutorial/README.md) |
| HTTP en profundidad | [DLRoute tutorial](https://github.com/dlunire/dlroute/blob/master/docs/tutorial/README.md) |
| Almacenamiento | [DLStorage](https://github.com/dlunire/dlstorage) |

---

Fin del tutorial del skeleton. Buen desarrollo con DLUnire.
