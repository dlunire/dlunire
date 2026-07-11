# 03 — Estructura del proyecto

**Licencia de este material:** AGPL-3.0-or-later.

El skeleton se mantiene **deliberadamente pequeño**. Añada carpetas solo cuando las necesite.

```
/
├── public/                 # Única raíz web
│   ├── index.php           # Entrada: autoload + Project::run()
│   ├── style.css           # Estilos de la bienvenida (ejemplo)
│   ├── welcome.js
│   └── favicon.*
├── app/                    # Su dominio (namespace DLUnire\)
│   ├── Controllers/        # p. ej. WelcomeController
│   ├── Models/             # p. ej. Users (demo auth)
│   ├── Auth/               # p. ej. Auth extends AuthBase
│   ├── Helpers/            # Se cargan al arrancar (todos los *.php)
│   ├── Constants/          # Se cargan al arrancar
│   └── Interfaces/
├── routes/
│   └── web.php             # Registro de rutas (GET / bienvenida)
├── resources/
│   ├── welcome.template.html
│   └── layouts/icons/      # Includes de plantilla
├── boot/                   # namespace Boot\
│   ├── Project.php         # Bootstrap Project::run()
│   ├── Authorizations.php  # CORS + DL_TOKEN
│   └── AuthorizationsInterface.php
├── dlunire/                # namespace Framework\ (capa del skeleton, no es DLCore)
│   ├── Auth/               # SystemCredentials, AuthBase, UserBase…
│   ├── Config/             # Controller, Token, Environment
│   ├── Errors/
│   └── Requests/
├── bin/
│   └── setup-env.php       # post-create-project: crea .env.type (+ spinner)
├── docs/tutorial/          # Este tutorial
├── tests/                  # PHPUnit
├── .env.type.example
├── LICENSE                 # AGPL-3.0-or-later
├── composer.json           # license: AGPL-3.0-or-later; post-create-project-cmd
└── vendor/                 # Composer (dlcore → dlroute, dlstorage)
```

## Namespaces (PSR-4)

| Prefijo | Carpeta |
|---------|---------|
| `DLUnire\` | `app/` |
| `Framework\` | `dlunire/` |
| `Boot\` | `boot/` |
| `Tests\` | `tests/` (autoload-dev) |

## Qué no versionar

| Ruta | Motivo |
|------|--------|
| `vendor/` | Composer |
| `.env.type` | Secretos locales (sí versionar `.env.type.example`) |
| `.build/` | Caché de plantillas compiladas por DLCore |

## Capas del ecosistema

| Capa | Paquete | Rol |
|------|---------|-----|
| Aplicación | `dlunire/dlunire` | Este skeleton |
| Kernel | `dlunire/dlcore` | ORM, vistas, `.env.type`, correo |
| HTTP | `dlunire/dlroute` | Rutas y peticiones |
| Persistencia binaria | `dlunire/dlstorage` | Credenciales / binario (transitiva vía dlcore) |

## Siguiente

[04-bootstrap.md](04-bootstrap.md)
