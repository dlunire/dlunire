# DLUnire

Framework PHP **orientado a API**: skeleton + kernel **DLCore**, HTTP **DLRoute** y
almacenamiento **DLStorage**. Rutas y controladores pensados para JSON/respuestas de
datos, ORM, `.env.type` tipado. Las plantillas HTML son opcionales (p. ej. la bienvenida
del skeleton); el uso principal es **backend y APIs**.

**Sitio:** [dlunire.dev](https://dlunire.dev) · **Tienda (licencia comercial):** [store.dlunire.dev](https://store.dlunire.dev)

---

## Requisitos

- PHP **≥ 8.2**
- Composer 2
- Extensiones habituales de PHP para web/BD (según su proyecto)

---

## Instalación

```bash
composer create-project dlunire/dlunire mi-app
cd mi-app
# Tras create-project se ejecuta bin/setup-env.php (crea .env.type si no existe)
# Edite .env.type (base de datos, prefijo, etc.)
composer run dev
```

Al crear el proyecto, Composer corre el script **`post-create-project-cmd`**:
genera `.env.type` copiando `.env.type.example` (con spinner en terminal interactiva).
Si el archivo ya existe, no lo sobrescribe. También puede lanzarlo a mano:

```bash
composer run setup-env
# o: php bin/setup-env.php
```

Abra `http://localhost:3000/`. Document root: `public/`.

Equivalente al servidor de desarrollo:

```bash
php -S localhost:3000 -t public/
```

### Extensión VS Code / Open VSX

Resaltado de `.env.type`: [DL Typed Environment](https://marketplace.visualstudio.com/items?itemName=dlunire.dlunire-envtype)

---

## Estructura (mínima)

```
/
├── public/           # Entrada web: index.php, style.css, welcome.js
├── app/              # Su código: Controllers, Models, Auth, Helpers…
├── routes/           # Rutas HTTP
├── resources/        # Plantillas (welcome + layouts/icons)
├── boot/             # Project::run(), CORS
├── dlunire/          # Capa base del skeleton (Controller, sesión, etc.)
├── tests/            # PHPUnit
├── .env.type.example
└── vendor/           # Composer (dlcore → dlroute, dlstorage)
```

Añada carpetas solo cuando las necesite (p. ej. más layouts, tests de feature).

**`.build/`** se crea solo al ejecutar la app: DLCore compila ahí las plantillas
(`resources/*.template.html` → PHP en caché). No la versiona; está en `.gitignore`.
Puede borrarla cuando quiera; se regenera al siguiente request.

| Capa | Paquete | Rol |
|------|---------|-----|
| Aplicación | `dlunire/dlunire` | Skeleton que usted personaliza |
| Kernel | `dlunire/dlcore` | ORM, vistas, `.env.type`, correo |
| HTTP | `dlunire/dlroute` | Rutas y peticiones |
| Persistencia | `dlunire/dlstorage` | Binario / credenciales cifradas |

`create-project` declara `dlcore`; DLRoute y DLStorage entran como dependencias transitivas.

---

## Inicio rápido (código)

### Rutas (`routes/web.php`)

```php
use DLRoute\Requests\DLRoute;

DLRoute::get('/api/health', fn () => ['status' => 'ok']);

DLRoute::get('/user/{id}', [UserController::class, 'show'])
    ->filter_by_type(['id' => 'integer']);
```

Tres formas: callback, `[Controller::class, 'method']`, o cadena `"Namespace\\Ctrl@method"`.

### Controladores

Extienden `Framework\Config\Controller`:

```php
$values = $this->get_values();
$email  = $this->get_email('email');
```

### Modelos y ORM (ejemplo rápido)

En `.env.type` (además de host/usuario/clave de MySQL):

```envtype
DL_DATABASE_NAME: string = "mi_app"
DL_DATABASE_DRIVE: string = "mysql"
DL_PREFIX: string = "dl_"
MULTITENANT: boolean = false
```

```php
namespace DLUnire\Models;

use DLCore\Database\Model;

final class Products extends Model {}
// Con DL_PREFIX = "dl_" → tabla dl_products
```

```php
use DLUnire\Models\Products;
use DLCore\Database\Model;

Products::create(['product_name' => 'Teclado', 'price' => 189000]);
$rows = Products::get(); // tope de seguridad (~1000 filas), no toda la tabla
$items = Products::where('price', '>', '100000')->get();
$page = Products::paginate(page: 1, rows: 20); // listados recomendados
// $all = Products::all(); // sin tope — solo si el conjunto es acotado
```

En la bienvenida del skeleton: sección **Inicio rápido · base de datos** (`#inicio-orm`).

### Entorno

Copie `.env.type.example` → `.env.type`. Cada variable tiene tipo estático
(`boolean`, `string`, `integer`, `email`, `uuid`, …).

CORS (API desde otro origen) se configura con hosts separados por comas:

```envtype
DL_CORS_ORIGINS: string = "localhost,127.0.0.1,app.ejemplo.com"
```

Si se omite, se permiten `localhost` y `127.0.0.1`.

---

## Funcionalidades en desarrollo

### DLAuth

Autenticador **básico** del kernel, **en desarrollo**. Sirve para demos o pruebas muy simples.
**No se recomienda** en aplicaciones reales ni en producción.

Mientras no exista un autenticador robusto en DLUnire, use otra solución en su capa de
aplicación (JWT, OAuth2/OIDC, librerías maduras, IdP de su infraestructura).

### MULTITENANT

La variable `MULTITENANT` puede declararse en `.env.type`, pero el modo SaaS
(una base por dominio) **aún no está terminado** (depende de **DLParse**).

En monoinquilino:

```envtype
MULTITENANT: boolean = false
```

---

## Licencia

Este proyecto se distribuye bajo **AGPL-3.0-or-later**.

- Texto: [`LICENSE`](./LICENSE)
- SPDX: [AGPL-3.0-or-later](https://spdx.org/licenses/AGPL-3.0-or-later.html)

Puede desarrollar y probar con libertad. Para **despliegue cerrado** a terceros por red
sin publicar el código de su aplicación bajo AGPL, vea la oferta comercial en
[store.dlunire.dev](https://store.dlunire.dev/).

---

## Pruebas

```bash
composer test
```

---

## Tutorial del skeleton

Guía progresiva (API first, bootstrap, rutas, ORM, licencia):

→ **[`docs/tutorial/README.md`](docs/tutorial/README.md)**

---

## TODO

- [ ] **Publicar la nueva versión del skeleton** (`dlunire/dlunire`) cuando estén listos los cambios en el **núcleo** (`dlunire/dlcore`) y, si aplica, en el **enrutador** (`dlunire/dlroute`). Hasta entonces no hay release de esta línea de trabajo.

Los cambios ya preparados para ese release están en **[CHANGELOG.md](CHANGELOG.md)** bajo **`[Unreleased]`**.

## Enlaces

| Recurso | URL |
|---------|-----|
| Sitio | https://dlunire.dev |
| Tienda / comercial | https://store.dlunire.dev |
| Repositorio | https://github.com/dlunire/dlunire |
| Tutorial skeleton | [docs/tutorial/](docs/tutorial/README.md) |
| Changelog | [CHANGELOG.md](CHANGELOG.md) |
| Tutorial kernel (DLCore) | https://github.com/dlunire/dlcore/blob/master/docs/tutorial/README.md |
| Tutorial DLRoute | https://github.com/dlunire/dlroute/blob/master/docs/tutorial/README.md |
| Tutorial DLStorage | https://github.com/dlunire/dlstorage |

---

## Autor

**David E Luna M** · DLUnire  
© 2026 · Todos los derechos reservados sobre la marca y materiales no cubiertos por la licencia de código.
