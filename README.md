# DLUnire

Framework PHP **orientado a API**: skeleton + kernel **DLCore**, HTTP **DLRoute** y
almacenamiento **DLStorage**. Proporciona enrutamiento, controladores, ORM,
almacenamiento binario cifrado y gestión de configuración mediante `.env.type`
tipado.

Las plantillas HTML son opcionales. La página de bienvenida del skeleton utiliza
una **SPA desarrollada con Svelte**, servida desde una plantilla PHP mínima.
El uso principal de DLUnire es el desarrollo de backends y APIs.

**Sitio:** [dlunire.dev](https://dlunire.dev) ·
**Tienda (licencia comercial):** [store.dlunire.dev](https://store.dlunire.dev)

---

## Requisitos

- PHP **>= 8.3**
- Composer 2
- Extensiones habituales de PHP para web y bases de datos, según su proyecto.

---

## Instalación

```bash
composer create-project dlunire/dlunire mi-app
cd mi-app

# Configure el entorno.
# .env.type se crea automáticamente si todavía no existe.
composer run dev
```

Al crear el proyecto, Composer ejecuta el script `post-create-project-cmd`,
que llama a `bin/setup-env.php`. Este script copia `.env.type.example` a
`.env.type` si el archivo todavía no existe; nunca sobrescribe una configuración
existente.

También puede ejecutarlo manualmente:

```bash
composer run setup-env

# Alternativamente:
php bin/setup-env.php
```

Abra `http://localhost:3000/`.

Document root: `public/`.

Equivalente al servidor de desarrollo:

```bash
php -S localhost:3000 -t public/
```

### Extensión VS Code / Open VSX

Resaltado de `.env.type`:

[DL Typed Environment](https://marketplace.visualstudio.com/items?itemName=dlunire.dlunire-envtype)

---

## Estructura del proyecto

```text
/
├── public/           # Entrada web de la aplicación
├── app/              # Código de la aplicación
├── routes/           # Definición de rutas HTTP
├── resources/        # Plantillas PHP y recursos de vistas
├── dist/             # Artefactos compilados de la SPA de bienvenida
├── boot/             # Inicialización del proyecto y configuración CORS
├── bin/              # Herramientas CLI
├── dlunire/          # Capa base del skeleton
├── docs/             # Documentación y tutoriales
├── tests/            # Pruebas PHPUnit
├── .env.type.example # Configuración de entorno de ejemplo
└── vendor/           # Dependencias instaladas mediante Composer
```

La carpeta `dist/` contiene los artefactos compilados de la SPA de bienvenida,
entre ellos `index.html`, `index.js` e `index.css`.

La carpeta `.build/` se genera durante la ejecución de la aplicación. DLCore
compila allí las plantillas (`resources/*.template.html`) en PHP para su
posterior ejecución. No se versiona y puede eliminarse: se regenera cuando
la aplicación vuelve a necesitarla.

### Arquitectura

| Capa | Paquete | Responsabilidad |
| ------ | --------- | ----------------- |
| Aplicación | `dlunire/dlunire` | Skeleton que el desarrollador personaliza |
| Kernel | `dlunire/dlcore` | ORM, plantillas, configuración y correo |
| HTTP | `dlunire/dlroute` | Enrutamiento y análisis de peticiones |
| Persistencia | `dlunire/dlstorage` | Almacenamiento binario y credenciales cifradas |
| Autenticación | `dlunire/dlauth` | Componentes de autenticación |

`create-project` declara DLCore como dependencia principal. DLRoute, DLStorage
y las demás dependencias del ecosistema se incorporan mediante Composer según
la configuración del paquete.

---

## Inicio rápido

### Rutas (`routes/web.php`)

```php
use DLRoute\Requests\DLRoute;

DLRoute::get('/api/health', fn () => ['status' => 'ok']);

DLRoute::get('/user/{id}', [UserController::class, 'show'])
    ->filter_by_type(['id' => 'integer']);
```

Puede registrar rutas mediante:

- Callbacks.
- Arrays de controlador y método: `[Controller::class, 'method']`.
- Cadenas con formato `"Namespace\\Ctrl@method"`.

### Controladores

Los controladores extienden `Framework\Config\Controller`.

```php
$values = $this->get_values();
$email  = $this->get_email('email');
```

### Modelos y ORM

En `.env.type`, además de la configuración de conexión a la base de datos:

```envtype
DL_DATABASE_NAME: string = "mi_app"
DL_DATABASE_DRIVE: string = "mysql"
DL_PREFIX: string = "dl_"
MULTITENANT: boolean = false
```

Ejemplo de modelo:

```php
namespace DLUnire\Models;

use DLCore\Database\Model;

final class Products extends Model {}
```

Con `DL_PREFIX = "dl_"`, la tabla correspondiente será `dl_products`.

Operaciones básicas:

```php
use DLUnire\Models\Products;

Products::create([
    'product_name' => 'Teclado',
    'price' => 189000
]);

$rows = Products::get();
// Límite de seguridad aproximado: 1000 filas.

$items = Products::where('price', '>', '100000')->get();

$page = Products::paginate(
    page: 1,
    rows: 20
);

// Products::all() obtiene todos los registros,
// por lo que debe utilizarse cuando el conjunto esté acotado.
```

Para listados, se recomienda utilizar `paginate()` cuando corresponda.

---

## Entorno y configuración

DLUnire utiliza `.env.type` para declarar variables con tipos estáticos,
por ejemplo:

- `boolean`
- `string`
- `integer`
- `email`
- `uuid`

Copie `.env.type.example` a `.env.type` si necesita preparar el entorno
manualmente. El instalador del proyecto realiza esta operación automáticamente
cuando el archivo no existe.

### CORS

Para permitir peticiones desde otros orígenes, configure `DL_CORS_ORIGINS`
con una lista de hosts separados por comas:

```envtype
DL_CORS_ORIGINS: string = "localhost,127.0.0.1,app.ejemplo.com"
```

Si se omite, se utilizan `localhost` y `127.0.0.1` como valores predeterminados.

---

## Página de bienvenida

La bienvenida del skeleton fue reestructurada en la versión `v2.0.0`.

Su arquitectura actual utiliza:

- **Svelte:** interfaz de usuario de la SPA.
- **Plantilla PHP mínima:** entrega la aplicación y sus parámetros de ejecución.
- **Bundle estático:** archivos JavaScript y CSS compilados.
- **CSP por nonce:** política de seguridad de contenido declarada en la plantilla.
- **SEO y metadatos sociales:** metadatos básicos, Open Graph y Twitter Cards.

El controlador `WelcomeController` expone el método `welcome()`, que renderiza
la vista `welcome` e inyecta el token y el callable del enrutador.

La implementación anterior, basada en varias vistas PHP y rutas independientes
para la bienvenida, fue eliminada.

---

## Funcionalidades en desarrollo

### DLAuth

DLAuth es un componente de autenticación del ecosistema que continúa en
desarrollo.

La versión disponible no debe considerarse un sistema de autenticación
completo para aplicaciones de producción. Para aplicaciones reales, utilice
una solución madura de autenticación en la capa correspondiente, como JWT,
OAuth2/OIDC o un proveedor de identidad de su infraestructura.

### MULTITENANT

La variable `MULTITENANT` puede declararse en `.env.type`, pero el modo SaaS
con resolución de bases de datos por dominio todavía no está terminado.
Su implementación depende del desarrollo de DLParse.

Para proyectos monoinquilino:

```envtype
MULTITENANT: boolean = false
```

---

## Licencia

DLUnire se distribuye bajo la licencia **AGPL-3.0-or-later**.

- Licencia: [`LICENSE`](./LICENSE)
- Identificador SPDX: [AGPL-3.0-or-later](https://spdx.org/licenses/AGPL-3.0-or-later.html)

El proyecto puede utilizarse, estudiarse y modificarse conforme a los términos
de la licencia.

Para escenarios de distribución o despliegue comercial cerrado que requieran
una licencia distinta, consulte la opción comercial:

[store.dlunire.dev](https://store.dlunire.dev/)

---

## Pruebas

Ejecute la suite de pruebas con:

```bash
composer test
```

---

## Documentación

La documentación y los tutoriales del skeleton se encuentran en proceso de
actualización para alinearse con la arquitectura introducida en `v2.0.0`.

- [Tutorial del skeleton](docs/tutorial/README.md)
- [Tutorial de DLCore](https://github.com/dlunire/dlcore/blob/master/docs/tutorial/README.md)
- [Tutorial de DLRoute](https://github.com/dlunire/dlroute/blob/master/docs/tutorial/README.md)
- [DLStorage](https://github.com/dlunire/dlstorage)

---

## Enlaces

| Recurso | URL |
| --------- | ----- |
| Sitio oficial | <https://dlunire.dev> |
| Tienda comercial | <https://store.dlunire.dev> |
| Repositorio | <https://github.com/dlunire/dlunire> |
| Tutorial skeleton | [docs/tutorial/](docs/tutorial/README.md) |
| Changelog | [CHANGELOG.md](CHANGELOG.md) |
| DLCore | <https://github.com/dlunire/dlcore> |
| DLRoute | <https://github.com/dlunire/dlroute> |
| DLStorage | <https://github.com/dlunire/dlstorage> |

---

## Autor

**David E Luna M** · DLUnire

© 2026 · Todos los derechos reservados sobre la marca y los materiales no
cubiertos por la licencia de código.
