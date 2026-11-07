# CampusSOS

**Sistema web de colaboración y apoyo entre estudiantes universitarios**

CampusSOS es una aplicación web desarrollada con Laravel, Vue 3, TypeScript e Inertia.js. Permite a los estudiantes publicar y gestionar solicitudes de ayuda dentro del campus universitario.

El proyecto incorpora un módulo de administración MVC que permite gestionar facultades, asignar estudiantes a ellas y alimentar un ranking de participación universitaria.

## 1. Objetivo del proyecto

Promover la colaboración entre estudiantes mediante una plataforma digital para solicitar ayuda, gestionar solicitudes y visualizar indicadores de participación por facultad.

El core propuesto contempla ayudas verificadas, niveles de colaboración, CampusPoints y reputación. Estas funcionalidades forman parte de la evolución prevista del sistema; actualmente se encuentra implementado el ranking basado en estudiantes registrados.

## 2. Tecnologías

- PHP y Laravel
- Vue.js 3 y TypeScript
- Inertia.js
- Tailwind CSS y SCSS
- SQLite para desarrollo local
- Eloquent ORM
- Git y GitHub
- Vite

## 3. Arquitectura MVC

El sistema utiliza el patrón Modelo-Vista-Controlador.

**Modelos:**
- `User`: administra usuarios, roles y su relación con facultades.
- `Facultad`: representa las facultades universitarias.
- `Solicitud`: representa las solicitudes de ayuda.

**Controladores:**
- `FacultadController`: operaciones CRUD de facultades.
- `EstudianteController`: asignación de estudiantes a facultades.
- `RankingController`: cálculo de indicadores de participación.
- `SolicitudController`: administración de solicitudes SOS.

**Vistas:**

La interfaz está desarrollada con Vue 3 e Inertia.js. Incluye formularios, listados, dashboard administrativo y ranking.

## 4. Administración MVC

El módulo administrativo está protegido mediante autenticación, verificación de correo y middleware de autorización para el rol `admin`.

### Administración de facultades

El administrador puede:

- Registrar nuevas facultades.
- Consultar las facultades registradas.
- Editar su nombre y población estudiantil.
- Eliminar facultades cuando las restricciones de integridad lo permitan.

Cada facultad registra un nombre y un total de estudiantes.

### Administración de estudiantes

El administrador puede consultar estudiantes y asignarlos a una facultad existente.

La relación se realiza mediante la clave foránea `users.facultad_id`, utilizando un dropdown con las facultades disponibles.

No se solicita al administrador ingresar manualmente el identificador de la facultad.

## 5. Validaciones en backend

Las validaciones se realizan en los controladores de Laravel antes de guardar información.

**Facultades:**

- El nombre es obligatorio.
- El nombre debe ser único.
- El total de estudiantes es obligatorio.
- El total de estudiantes debe ser un número entero mayor o igual a uno.

La población estudiantil es un dato crítico para el cálculo de participación, porque constituye el denominador del indicador utilizado en el ranking.

**Asignación de estudiantes:**

- La facultad seleccionada es obligatoria.
- El identificador debe ser un número entero.
- La facultad debe existir en la tabla `facultades`.
- La operación solo se permite sobre usuarios con rol `estudiante`.

Estas validaciones se ejecutan en el servidor y no dependen únicamente de JavaScript.

## 6. Relaciones de base de datos

El sistema utiliza las siguientes relaciones:

- Una facultad puede tener varios usuarios estudiantes.
- Cada usuario puede pertenecer a una facultad.
- Cada solicitud pertenece a un usuario.

La base de datos local utiliza SQLite. La estructura se define mediante migraciones de Laravel.

## 7. Ranking de participación

El ranking utiliza información administrada desde el módulo MVC.

Para cada facultad, calcula:

**Porcentaje de participación = (Estudiantes registrados en la facultad / Total de estudiantes de la facultad) × 100**

El ranking ordena las facultades según el porcentaje calculado.

Este indicador representa la participación de usuarios registrados en CampusSOS. No constituye todavía el índice completo de colaboración basado en ayudas verificadas y CampusPoints.

## 8. Gestión de solicitudes SOS

Los estudiantes autenticados pueden:

- Crear solicitudes de ayuda.
- Consultar sus solicitudes.
- Editar solicitudes permitidas.
- Eliminar sus solicitudes.
- Clasificar solicitudes por urgencia.
- Programar solicitudes mediante fechas.
- Consultar estados temporales como programada, activa o expirada.

Las operaciones están protegidas mediante autenticación y controles de acceso.

## 9. Instalación local

**Requisitos:**

- PHP y Composer
- Node.js y npm
- SQLite habilitado en PHP
- Git

Clonar el repositorio:

```bash
git clone https://github.com/StevenCuenca/campus-sos.git
cd campus-sos
```

Instalar dependencias:

```bash
composer install
npm install
```

Crear el archivo de entorno:

```bash
cp .env.example .env
```

En PowerShell puede utilizarse:

```powershell
Copy-Item .env.example .env
```

Generar la clave de aplicación:

```bash
php artisan key:generate
```

Configurar SQLite en `.env`:

```env
DB_CONNECTION=sqlite
```

Crear el archivo `database/database.sqlite` si todavía no existe y ejecutar:

```bash
php artisan migrate
```

Iniciar el entorno de desarrollo:

```bash
composer run dev
```

La aplicación normalmente estará disponible en `http://localhost:8000`.

## 10. Roles y seguridad

**Estudiante:** puede acceder a las funcionalidades estudiantiles y administrar sus solicitudes según las restricciones del sistema.

**Administrador:** puede acceder al módulo administrativo para gestionar facultades y asignaciones.

El sistema utiliza middleware de Laravel para controlar el acceso. Las contraseñas se almacenan mediante hashing.

La asignación del rol administrador se realiza desde el backend por una persona autorizada; no está disponible como una opción pública del formulario de registro.

## 11. Pruebas y compilación

Ejecutar las pruebas automatizadas:

```bash
php artisan test
```

Generar los recursos de producción:

```bash
npm run build
```

En la comprobación realizada durante el desarrollo se obtuvieron 23 pruebas aprobadas, 3 omitidas y una compilación de producción exitosa. Las pruebas omitidas corresponden a funcionalidades de autenticación de dos factores no habilitadas.

Estas pruebas no sustituyen las comprobaciones específicas de las funcionalidades administrativas.

## 12. Estado del proyecto

**Implementado:**
- Autenticación y roles.
- CRUD de solicitudes.
- Administración MVC de facultades.
- Asignación de estudiantes mediante dropdown.
- Validaciones de backend.
- Ranking dinámico de participación.
- Versionamiento en GitHub.

**Pendiente o planificado:**
- Índice completo de colaboración.
- Ayudas verificadas y CampusPoints.
- Despliegue público y su verificación.
- Video demostrativo de la administración.

## 13. Repositorio

https://github.com/StevenCuenca/campus-sos

## 14. Autor

**Steven Cuenca**

Proyecto académico desarrollado para la materia de Ingenieria Web.