# Changelog / Registro de Cambios

All notable changes to this project will be documented in this file.  
Todos los cambios importantes de este proyecto serán documentados en este archivo.

This project adheres to [Semantic Versioning](https://semver.org/) and follows
[Keep a Changelog](https://keepachangelog.com/).  
Este proyecto sigue [Versionado Semántico](https://semver.org/lang/es/) y el
formato [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/).

---

## [Unreleased]

> **Not yet published.** Changes below are ready for the next skeleton release.  
> **Aún no publicado.** Los cambios siguientes quedan listos para la próxima
> versión del skeleton. El release se hará cuando estén alineados el **núcleo**
> (`dlunire/dlcore`) y, si aplica, el **enrutador** (`dlunire/dlroute`).  
> See also the **TODO** in `README.md`.

### Added / Añadido

- **Privacy policy page:** `docs/POLITICA-DATOS.md` (Ley 1581 / Colombia), view
  `docs-politica-datos`, route `GET /privacy-policy` (`WelcomeController::privacy_policy`).
  Linked from welcome header, footer and legal note.
- **Política de datos:** `docs/POLITICA-DATOS.md`, plantilla `docs-politica-datos`,
  ruta `GET /privacy-policy`. Enlaces en menú, pie y nota legal de la bienvenida.

- **`$route` / `Router::to` in welcome & docs:** assets (`favicon.svg`, `style.css`,
  `welcome.js`), home, site path `privacy-policy`, and fragments (`$route('/#…')`)
  go through the router callable injected by `WelcomeController`. External
  `https://…` URLs unchanged.
- **`$route` / `Router::to` en welcome y docs:** assets, inicio, `privacy-policy` y
  anclas vía callable del enrutador. URLs externas sin cambio.

- **Welcome buttons aligned with DLCore:** flat primary/ghost (soft green fill +
  border, no gradient/shadow/lift); same light-theme tokens and `.welcome`
  overrides as the kernel page.
- **Botones de bienvenida alineados con DLCore:** primary/ghost planos (relleno
  verde suave + borde, sin gradiente/sombra/elevación); mismos tokens en tema
  claro y overrides `.welcome`.

- **Floating nav bar (DLCore design):** `header--float` + glass `header__bar`,
  progress strip, scoped menu labels (page/site/external), metrics via
  `getBoundingClientRect` (`--header-offset` / scroll-margin). Isotipo without
  inline `<style>`.
- **Barra de navegación flotante (diseño DLCore):** pastilla glass, progreso,
  etiquetas de alcance en el menú y métricas con `getBoundingClientRect`.
  Isotipo sin CSS inline.

- **WIP section redesign:** replaced nested “En desarrollo” panels with a dedicated
  section (`wip-card` grid for DLAuth / MULTITENANT) and a compact Composer
  `stack-assemble` strip under architecture.
- **Sección WIP rediseñada:** tarjetas `wip-card` (DLAuth / MULTITENANT) y franja
  `stack-assemble` de Composer bajo arquitectura, sin paneles anidados.

- **Welcome CSP + dual favicons (as DLCore):** per-request Content-Security-Policy
  (HTTP header + meta, `$csp` / `$token` nonces on CSS/JS; `script-src`/`style-src`
  without `'self'`). Favicons: `favicon.svg` (default/light) + `favicon-dark.svg`
  (`prefers-color-scheme: dark`). Privacy page shares CSP; no inline styles.
- **CSP en welcome + favicons claro/oscuro (como DLCore):** CSP por petición
  (cabecera + meta, nonces en CSS/JS). Favicons `favicon.svg` y `favicon-dark.svg`.
  Política de datos con la misma CSP; sin estilos inline.

- **API-first orientation:** the skeleton is documented and structured as a PHP
  framework for **APIs** (JSON/data responses primary; HTML templates optional).
- **Orientation API first:** el skeleton se documenta y estructura como framework
  PHP orientado a **APIs** (respuestas JSON/datos como camino principal; plantillas
  HTML opcionales).

- **Progressive tutorial** under `docs/tutorial/` (12 chapters): what is DLUnire,
  install, structure, bootstrap `Project::run()`, `.env.type`, routes/JSON,
  controllers, ORM (`get` / `all` / `paginate`), helpers, CORS/session, in-progress
  features (DLAuth, MULTITENANT), AGPL & commercial store.
- **Tutorial progresivo** en `docs/tutorial/` (12 capítulos): qué es DLUnire,
  instalación, estructura, bootstrap `Project::run()`, `.env.type`, rutas/JSON,
  controladores, ORM (`get` / `all` / `paginate`), helpers, CORS/sesión, funciones
  en desarrollo (DLAuth, MULTITENANT), AGPL y tienda comercial.

- **`bin/setup-env.php`:** post-`create-project` helper that copies
  `.env.type.example` → `.env.type` when missing (does not overwrite). CLI
  **spinner** on interactive TTY; plain output in CI/non-TTY.
- **`bin/setup-env.php`:** tras `create-project` copia `.env.type.example` →
  `.env.type` si no existe (no sobrescribe). **Spinner** en TTY interactiva;
  salida simple en CI / no TTY.

- Composer scripts:
  - `post-create-project-cmd` → `@php bin/setup-env.php`
  - `setup-env` → same script for manual runs (`composer run setup-env`)
- Scripts de Composer:
  - `post-create-project-cmd` → `@php bin/setup-env.php`
  - `setup-env` → mismo script a mano (`composer run setup-env`)

- **Welcome page** assets: slim `resources/welcome.template.html`, `public/style.css`,
  `public/welcome.js`, isotipo in `resources/layouts/icons/`, updated logo/favicon.
- **Bienvenida** del skeleton: plantilla reducida, estilos/JS en `public/`, isotipo
  y logotipo/favicon actualizados.

- **Environment / CORS:** `DL_CORS_ORIGINS` in `.env.type.example`; bootstrap reads
  hosts (comma-separated) with defaults `localhost` / `127.0.0.1`. Optional
  `DL_TOKEN` Bearer check documented with `Boot\Authorizations`.
- **Entorno / CORS:** `DL_CORS_ORIGINS` en `.env.type.example`; el bootstrap lee
  hosts (separados por comas) con valores por defecto `localhost` / `127.0.0.1`.
  `DL_TOKEN` (Bearer) documentado junto a `Boot\Authorizations`.

- **Session cookie lifetime** aligned with `DL_LIFETIME` in
  `Framework\Auth\SystemCredentials` (HttpOnly, SameSite=Lax, Secure when HTTPS).
- **Vida de cookie de sesión** alineada con `DL_LIFETIME` en
  `Framework\Auth\SystemCredentials` (HttpOnly, SameSite=Lax, Secure si HTTPS).

- **PHPUnit smoke tests** (`tests/Unit/ProjectStructureTest.php`): public entry,
  `.env.type.example` keys, `clear_route` preserves dots, AGPL license, autoload,
  tutorial presence, `setup-env` registration.
- **Pruebas de humo PHPUnit** (`tests/Unit/ProjectStructureTest.php`): entrada
  pública, claves de `.env.type.example`, `clear_route` conserva puntos, licencia
  AGPL, autoload, tutorial y registro de `setup-env`.

### Changed / Cambiado

- **`Boot\Project::run(bool $autoload_routes = true)`:** merges **DLCore** bootstrap
  phases (Constants → Helpers → optional `routes/` via `Path::ensure_dir` +
  `require_once`, same contract as `DLCore\Boot\Project::run`) with **skeleton**
  behavior (`SystemCredentials::load()`, CORS from `DL_CORS_ORIGINS`,
  `Boot\Authorizations`). Keeps skeleton helpers `includes()` / `clear_route()`.
  `public/index.php` loads autoload with `DIRECTORY_SEPARATOR`.
- **`Boot\Project::run(bool $autoload_routes = true)`:** combina las fases de
  arranque de **DLCore** (Constants → Helpers → `routes/` opcional con
  `Path::ensure_dir` + `require_once`, mismo contrato que
  `DLCore\Boot\Project::run`) con el **skeleton** (`SystemCredentials::load()`,
  CORS desde `DL_CORS_ORIGINS`, `Boot\Authorizations`). Se conservan
  `includes()` / `clear_route()`. `public/index.php` usa `DIRECTORY_SEPARATOR`
  para el autoload.

- **License:** package and skeleton materials under **`AGPL-3.0-or-later`**
  (`LICENSE`, `composer.json` `"license"`, PHP file headers / `@license`, tutorial
  footers). Commercial closed deployment: [store.dlunire.dev](https://store.dlunire.dev/).
- **Licencia:** paquete y materiales del skeleton bajo **`AGPL-3.0-or-later`**
  (`LICENSE`, `composer.json`, cabeceras PHP / `@license`, tutorial). Despliegue
  cerrado comercial: [store.dlunire.dev](https://store.dlunire.dev/).

- **README** rewritten for API-first usage: install, structure, quick routes,
  ORM notes (`get` safety cap ~1000, `all` unlimited, prefer `paginate`), env,
  CORS, in-development features, license, tests, tutorial link, **TODO** (do not
  publish skeleton release until core / router work is ready).
- **README** reescrito orientado a API: instalación, estructura, rutas, ORM
  (tope de seguridad de `get` ~1000, `all` sin tope, preferir `paginate`), entorno,
  CORS, funciones en desarrollo, licencia, pruebas, tutorial y **TODO** (no
  publicar el release del skeleton hasta alinear núcleo / enrutador).

- **Slim skeleton surface:** single welcome route (`WelcomeController`), helpers
  retained, demo bulk reduced so the app stays small by default.
- **Superficie del skeleton reducida:** ruta de bienvenida (`WelcomeController`),
  helpers conservados, demos voluminosas recortadas para un árbol pequeño por defecto.

- **`Boot\Project::clear_route()`:** normalizes path separators only; **does not**
  strip dots (paths like `my.app` stay valid).
- **`Boot\Project::clear_route()`:** solo normaliza separadores; **no** elimina
  puntos (rutas como `my.app` siguen siendo válidas).

- **Dependency line:** `dlunire/dlcore` `^2.1` (DLRoute / DLStorage transitively).
  Updating **dlstorage** inside DLCore remains a **core maintainer** task, not this
  skeleton package.
- **Dependencias:** `dlunire/dlcore` `^2.1` (DLRoute / DLStorage transitivas).
  Actualizar **dlstorage** en DLCore es tarea del **mantenedor del núcleo**, no de
  este paquete skeleton.

### Removed / Eliminado

- Heavy demo / frontend bulk not needed for an API-first skeleton: SASS modules,
  Prism bundles, Google reCAPTCHA layout samples, old `home`/header markdown demos,
  `TestController`, legacy preview assets (as applicable in the slim pass).
- Carga de demos / frontend no necesaria para un skeleton API first: módulos SASS,
  bundles Prism, layouts de ejemplo reCAPTCHA, demos markdown home/header,
  `TestController`, assets de preview antiguos (según el recorte slim).

- Local editor / cache noise from the tree where applicable (e.g. committed
  PHPUnit result cache, local `.vscode` package noise) so installs stay clean.
- Ruido local de editor/caché del árbol cuando aplica (p. ej. caché de resultados
  PHPUnit versionada, ruido local de `.vscode`) para instalaciones más limpias.

### Fixed / Corregido

- Bootstrap / auth edge cases addressed during the slim pass (session lifetime vs
  `DL_LIFETIME`, CORS origin host parsing, route include paths with dots).
- Casos límite de bootstrap/auth en el recorte slim (vida de sesión vs
  `DL_LIFETIME`, parseo de hosts CORS, includes de rutas con puntos en el path).

### Notes / Notas

- **Publish gate:** tag/release of this skeleton line is intentionally **deferred**
  until planned work on **DLCore** (and possibly **DLRoute**) is done.
- **Puerta de publicación:** el tag/release de esta línea del skeleton queda
  **aplazado** a propósito hasta completar el trabajo previsto en **DLCore** (y
  posiblemente **DLRoute**).

- **DLAuth** and **MULTITENANT** remain **in development**; production apps should
  use external auth solutions until a robust DLAuth library is ready. Multitenant
  resolution depends on future **DLParse** / `dlunire.type` work.
- **DLAuth** y **MULTITENANT** siguen **en desarrollo**; en producción use otra
  solución de autenticación hasta que exista un DLAuth robusto. El multitenant
  completo depende del futuro **DLParse** / `dlunire.type`.

---

## [v1.0.3]

Previous stable tags on the `v1.0.x` line (see Git tags).  
Etiquetas estables previas de la línea `v1.0.x` (ver tags de Git).

## [v1.0.2]

## [v1.0.1]

## [v1.0.0]

---

[Unreleased]: https://github.com/dlunire/dlunire/compare/v1.0.3...HEAD
[v1.0.3]: https://github.com/dlunire/dlunire/releases/tag/v1.0.3
[v1.0.2]: https://github.com/dlunire/dlunire/releases/tag/v1.0.2
[v1.0.1]: https://github.com/dlunire/dlunire/releases/tag/v1.0.1
[v1.0.0]: https://github.com/dlunire/dlunire/releases/tag/v1.0.0
