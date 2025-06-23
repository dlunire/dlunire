# DLUnire Framework — Biografía del Proyecto

**DLUnire** es un **framework** PHP moderno diseñado para ofrecer una experiencia de desarrollo backend sencilla, elegante y productiva. Inspirado en herramientas como Laravel, `DLUnire` incorpora un sistema de plantillas con directivas personalizadas, una estructura modular clara, y soporte para programación orientada a objetos con tipado fuerte.

---

## Desarrollador del proyecto

Este proyecto ha sido desarrollado por David E Luna M, creador de iniciativas como "Códigos del Futuro" (@cdelfuturo) y del Framework DLUnire, una herramienta orientada al desarrollo web que integra funcionalidades avanzadas tanto en el backend como en el frontend.

## Filosofía del Proyecto

`DLUnire` busca ser una herramienta de desarrollo rápida, eficiente y estructurada para aplicaciones web pequeñas o medianas, con una sintaxis comprensible y una arquitectura ligera. Gracias a su estructura intuitiva, puede ser adoptado fácilmente tanto por desarrolladores nuevos como por programadores con experiencia en PHP.

---

## Características destacadas

- ✨ Motor de plantillas con directivas similares a Blade de Laravel.
- 📦 Instalación vía Composer:  
  ```bash
  composer create-project dlunire/dlunire tu-app
  ```
- 🔍 Soporte para variables de entorno con tipos estáticos usando un archivo `.env.type`.
- 🎨 Integración directa con `SASS/SCSS` para desarrollo de estilos.
- 🚦 Sistema de rutas poderoso inspirado en Laravel, pero optimizado para simplicidad.
- 🔐 Estructura modular para controladores, autenticación, constantes globales, helpers, interfaces, y modelos.
- ⚙️ ORM incluido vía `DLCore\Database\Model`, con detección automática de tablas y soporte para paginación.
- ✅ Soporte para métodos HTTP `GET`, `POST`, `PUT`, `PATCH`, y `DELETE`.
- 🧪 Estructura lista para pruebas automatizadas.

---

## Estructura de Directorios

La estructura del proyecto está organizada de la siguiente manera:

```
Raíz /
    |- /public/        # Punto de entrada de la aplicación
    |- /app/
        |- /Models/
        |- /Auth/
        |- /Constants/
        |- /Controllers/
        |- /Helpers/
        |- /Interfaces/
    |- /routes/        # Definición de rutas sin necesidad de `require`
    |- /resources/     # Vistas con directivas tipo Blade
    |- /tests/         # Pruebas automatizadas
    |- /dlunire/       # Núcleo del framework
```

---

## Extensiones complementarias

### Resaltador de variables de entorno

Para mejorar la experiencia de desarrollo, se recomienda instalar la extensión para VS Code:  
🔌 `DL Typed Environment`  
[Descargar desde Visual Studio Marketplace](https://marketplace.visualstudio.com/items?itemName=dlunamontilla.envtype)

---

## Rutas HTTP

DLUnire soporta tres formas de definir rutas:

1. **Como cadena de texto apuntando al controlador**:
   ```php
   DLRoute::get('/', "DLUnire\\Controllers\\TestController@method");
   ```

2. **Como función callback anónima**:
   ```php
   DLRoute::get("/", function() {
       return view('vista');
   });
   ```

3. **Como arreglo tipo controlador::método**:
   ```php
   DLRoute::get("/user/{id}", [TestController::class, 'method']);
   ```

Soporta parámetros dinámicos, captura automática y subida de archivos.

---

## Controladores

Los controladores heredan de una clase base `Framework\Config\Controller`. Permiten acceder a valores de la petición de forma segura:

```php
$values = $this->get_values(); // Devuelve los campos del formulario o JSON enviado a través del cliente HTTP
$email  = $this->get_email('email'); // "email" es el campo del formulario donde se envía el correo electrónico. Valida automática el correo.
$uuid   = $this->get_uuid('uuid'); // "uuid" es el campo del formulario que valida el formato UUIDv4.
```

---

## Modelos

Definidos dentro de `app/Models`, los modelos heredan de `DLCore\Database\Model`:

```php
final class Users extends Model {}
```

Esto habilita consultas como:

```php
$users = Users::get(); // Devuelve los registros de la base de datos
$users = Users::paginate($page, $rows); // Devuelve los registros de la base de datos por página.
```

La clase define automáticamente la tabla si su nombre coincide. También puedes asignarla manualmente con:

```php
# Puedes establecer un nombre diferente de tabla aquí. Sin embargo, puedes definir una
# consulta aquí también, que será convertida automáticamente en vista:
protected static ?string $table = "otra_tabla";
```

---

## Visión a futuro

DLUnire aún está en desarrollo activo. La documentación completa está en proceso y nuevas funcionalidades están siendo diseñadas. El objetivo es que DLUnire evolucione hacia un microframework PHP robusto, con enfoque en extensibilidad, rendimiento y claridad sintáctica.

---

## Enlaces de interés

- [Sitio Web Oficial](https://dlunire.pro "DLUnire Framework")
- 🌐 [Repositorio del Framework](https://github.com/dlunire/dlunire)
- 📦 Instálalo:  
  ```bash
  composer create-project dlunire/dlunire tu-app
  ```
