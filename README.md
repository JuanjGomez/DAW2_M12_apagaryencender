# Sistema de Gestión de Incidencias

> **⚠️ CREDENCIALES DE PRUEBA**
> 
> Para acceder al sistema, utiliza cualquiera de los siguientes correos con la contraseña: `qweQWE123`
> - Administrador: `admin@empresa.com`
> - Cliente: `cliente@empresa.com`
> - Técnico Barcelona: `tecnico@empresa.com`
> - Gestor Berlín: `gestor@empresa.com`
> - Técnico Berlín: `tecnico.berlin@empresa.com`
> - Técnico Montreal: `tecnico.montreal@empresa.com`

Este es un sistema de gestión de incidencias desarrollado con Laravel, diseñado para manejar y dar seguimiento a incidencias en diferentes sedes de una empresa.

## Características Principales

- Gestión de incidencias multiempresa
- Sistema de roles y permisos
- Chat en tiempo real para comunicación sobre incidencias
- Gestión de sedes internacionales
- Sistema de prioridades y estados para incidencias
- Panel de administración completo

## Requisitos del Sistema

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js y NPM

## Instalación

1. Clonar el repositorio
2. Ejecutar `composer install`
3. Copiar `.env.example` a `.env` y configurar la base de datos
4. Ejecutar `php artisan key:generate`
5. Ejecutar `php artisan migrate --seed`
6. Ejecutar `npm install && npm run dev`

## Usuarios del Sistema

El sistema viene con los siguientes usuarios preconfigurados para pruebas. Todos los usuarios utilizan la misma contraseña: `qweQWE123`

### Credenciales de Acceso

| Rol | Email | Sede |
|-----|-------|------|
| Administrador | admin@empresa.com | Barcelona |
| Cliente | cliente@empresa.com | Barcelona |
| Técnico | tecnico@empresa.com | Barcelona |
| Gestor | gestor@empresa.com | Berlín |
| Técnico | tecnico.berlin@empresa.com | Berlín |
| Técnico | tecnico.montreal@empresa.com | Montreal |

### Roles y Permisos

- **Administrador**: Acceso total al sistema, gestión de usuarios y configuraciones
- **Gestor**: Gestión de equipos técnicos y supervisión de incidencias
- **Técnico**: Resolución de incidencias y comunicación con clientes
- **Cliente**: Creación y seguimiento de incidencias

## Estructura de Sedes

El sistema está configurado con las siguientes sedes:
- Barcelona (Sede Principal)
- Berlín
- Montreal

## Soporte

Para reportar problemas o solicitar nuevas características, por favor crear un issue en el repositorio.

## Licencia

Este proyecto está licenciado bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.
