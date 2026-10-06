# CampusSOS

CampusSOS es una aplicación web colaborativa orientada a estudiantes universitarios. Su objetivo es permitir que los usuarios publiquen solicitudes de ayuda dentro del campus y administren sus propias solicitudes de forma sencilla.

Este proyecto fue desarrollado como parte de la materia de Desarrollo Web, aplicando el patrón MVC, operaciones CRUD y autenticación de usuarios.

## Funcionalidades

- Registro e inicio de sesión de usuarios.
- Autenticación mediante Laravel.
- Protección de rutas para usuarios no autenticados.
- Dashboard personalizado.
- Creación de solicitudes SOS.
- Visualización de las solicitudes del usuario.
- Edición de solicitudes.
- Eliminación de solicitudes.
- Clasificación por nivel de urgencia.
- Solicitudes inmediatas o programadas.
- Estados temporales: programada, activa y expirada.
- Restricción de edición para solicitudes expiradas.
- Cada usuario administra únicamente sus propias solicitudes.

## CRUD

La aplicación implementa las cuatro operaciones principales de un CRUD:

- **Create:** publicar una nueva solicitud SOS.
- **Read:** consultar las solicitudes publicadas.
- **Update:** modificar una solicitud existente.
- **Delete:** eliminar una solicitud.

## Autenticación y seguridad

CampusSOS utiliza el sistema de autenticación de Laravel.

Las rutas del dashboard y del CRUD están protegidas mediante middleware, por lo que un usuario no autenticado no puede acceder directamente a estas secciones.

Las contraseñas no se almacenan en texto plano. Laravel utiliza hashing seguro para proteger las contraseñas almacenadas en la base de datos.

## Arquitectura MVC

El proyecto aplica el patrón **Modelo - Vista - Controlador (MVC)**.

### Modelo

Los modelos representan y administran los datos de la aplicación.

Ejemplos:

- `User`
- `Solicitud`

### Vista

La interfaz de usuario está desarrollada utilizando Vue.js e Inertia.

Las vistas permiten al usuario interactuar con el dashboard, formularios y solicitudes.

### Controlador

`SolicitudController` contiene la lógica necesaria para administrar las solicitudes y ejecutar las operaciones CRUD.

## Tecnologías utilizadas

- Laravel
- PHP
- Vue.js 3
- TypeScript
- Inertia.js
- Tailwind CSS
- SQLite
- Node.js
- npm
- Git
- GitHub
- GitHub Actions

## Instalación

Clonar el repositorio:

```bash
git clone https://github.com/StevenCuenca/campus-sos.git
```

Ingresar al proyecto:

```bash
cd campus-sos
```

Instalar las dependencias:

```bash
composer install
npm install
```

Crear el archivo de configuración:

```bash
cp .env.example .env
```

Generar la clave de Laravel:

```bash
php artisan key:generate
```

Ejecutar las migraciones:

```bash
php artisan migrate
```

Iniciar el entorno de desarrollo:

```bash
composer run dev
```

La aplicación estará disponible normalmente en:

`http://localhost:8000`

## Control de calidad

El proyecto utiliza GitHub Actions para verificar automáticamente el formato del código, análisis estático y demás comprobaciones configuradas en el proyecto.

## Autor

**Steven Cuenca**

Proyecto académico desarrollado para la materia de Desarrollo Web.