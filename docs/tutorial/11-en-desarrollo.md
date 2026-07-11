# 11 — En desarrollo y límites

**Licencia de este material:** AGPL-3.0-or-later.

## DLAuth

Autenticador **básico** del kernel, **en desarrollo**.

- Sirve para demos o pruebas muy simples.
- **No se recomienda** en aplicaciones reales ni en producción.
- Mientras no exista un autenticador robusto en DLUnire, integre **otra solución**
  en su capa de aplicación: JWT, OAuth2/OIDC, librerías maduras, IdP de su infra.

Clases del skeleton (`Framework\Auth\AuthBase`, `app\Auth\Auth`, `Users` como
`UserBase`) se apoyan en ese camino: úselas solo si entiende el estado actual.

## MULTITENANT

- Variable en `.env.type`: `MULTITENANT: boolean = false`
- Modo SaaS (una base por dominio) **aún no está terminado** (depende de **DLParse**).
- En monoinquilino: **siempre `false`**.

## ORM: no confunda `get()` con “todo”

Ver [08-orm.md](08-orm.md): `get()` tiene tope de seguridad; `all()` es a riesgo
del programador; listados con `paginate()`.

## Siguiente

[12-licencia.md](12-licencia.md)
