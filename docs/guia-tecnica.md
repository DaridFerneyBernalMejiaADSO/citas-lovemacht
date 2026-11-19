# Guía técnica del código

Esta guía explica cómo se organiza Lovematch y dónde encontrar cada comportamiento. La aplicación usa Laravel 12, PHP 8.2 o superior y MariaDB. No necesita Node.js.

## Inicio de una solicitud

1. `public/index.php` inicia Laravel.
2. `bootstrap/app.php` carga la aplicación y sus servicios.
3. `routes/web.php` decide qué controlador atiende la dirección y qué permisos necesita.
4. El controlador valida la petición y usa los modelos de `app/Models`.
5. Laravel devuelve una vista Blade de `resources/views`.
6. Las vistas cargan los estilos y fotos locales de `public/assets`.

## Carpetas y responsabilidades

### `app/Http/Controllers`

- `HomeController.php`: carga el perfil ficticio destacado de la portada.
- `AuthController.php`: muestra registro e inicio de sesión, registra usuarios adultos, inicia y cierra sesiones.
- `ProfileController.php`: muestra y actualiza el perfil del usuario autenticado, incluida su foto.
- `DiscoverController.php`: presenta perfiles elegibles y registra un pase o un me gusta.
- `MatchController.php`: muestra las coincidencias mutuas.
- `ChatController.php` y `MessageController.php`: autorizan la conversación y guardan mensajes entre participantes.
- `SupportController.php`: permite que un usuario cree y consulte solicitudes de ayuda.
- `AdminSupportController.php`: permite al administrador responder y cerrar solicitudes.

Los controladores reciben las peticiones; las reglas de acceso se mantienen en las rutas y el middleware, no en la vista.

### `app/Http/Middleware`

- `RoleMiddleware.php`: impide que una cuenta entre en las páginas asignadas a otro rol.

`routes/web.php` separa las páginas públicas, las que requieren sesión, las de `user` y las de `admin`. El registro público siempre asigna el rol `user`.

### `app/Models`

- `User.php`: cuenta, correo, rol y relación con su perfil.
- `Profile.php`: nombre, edad, ciudad, biografía e imagen.
- `Swipe.php`: decisión de pasar o indicar interés.
- `ConnectionMatch.php`: coincidencia mutua almacenada en `matches`.
- `Message.php`: mensaje perteneciente a una coincidencia.
- `SupportRequest.php`: solicitud de ayuda y respuesta administrativa.

Los modelos describen cómo leer y guardar cada tabla; las migraciones definen la estructura física.

### Autenticación heredada

`app/Providers/LegacyUserProvider.php` reconoce los hashes HMAC antiguos. Si la contraseña anterior es correcta, Laravel reemplaza el hash por bcrypt sin cambiar la contraseña que escribe la persona. La clave heredada se configura como `LEGACY_PASSWORD_KEY` en el `.env` local y nunca debe publicarse.

### Rutas y vistas

- `routes/web.php`: URLs, nombres de rutas y grupos de autorización.
- `resources/views/layouts/app.blade.php`: navegación general y navegación móvil.
- `resources/views/layouts/auth.blade.php`: diseño de inicio de sesión y registro.
- `resources/views/auth/`: formularios de acceso y registro.
- `resources/views/social/`: descubrimiento, coincidencias, perfil, chats y ayuda.
- `resources/views/admin/support.blade.php`: bandeja de solicitudes del administrador.
- `resources/views/home.blade.php`: portada y perfil destacado.
- `public/assets/css/styles.css`: estilo general y componentes sociales.
- `public/assets/css/auth.css`: estilo de acceso y registro.

La vista PHP anterior `app/views/update.view.php` fue reemplazada por `resources/views/social/profile-edit.blade.php` al migrar a Laravel.

En Blade, `{{ ... }}` imprime valores escapados. Los formularios que modifican datos incluyen token CSRF y usan validación de Laravel.

### Base de datos

`database/migrations/2026_04_19_000001_create_lovematch_schema.php` crea las tablas de una instalación nueva o convierte el esquema anterior preservando sus registros. La conversión no admite rollback automático: antes de usarla sobre una base existente hay que hacer un respaldo.

`database/seeders/DatabaseSeeder.php` crea o actualiza perfiles de muestra sin duplicarlos. Las seis tablas son `users`, `profiles`, `swipes`, `matches`, `messages` y `support_requests`.

### Pruebas

- `tests/Feature/RegistrationTest.php`: mayoría de edad y rol asignado al registrarse.
- `tests/Feature/LegacyPasswordTest.php`: acceso con hash anterior y actualización a bcrypt.
- `tests/Feature/SocialFlowTest.php`: descubrimiento, coincidencias, chat y permisos de soporte.

Ejecuta `php artisan test` después de hacer cambios.

## Flujos principales

### Registro e inicio de sesión

La persona crea una cuenta con fecha de nacimiento y contraseña. El servidor rechaza menores de 18, guarda el rol `user` y crea su perfil. Al iniciar sesión, Laravel consulta el proveedor de autenticación y mantiene compatibilidad con los hashes anteriores.

### Descubrir y conversar

El usuario ve perfiles adultos que no ha pasado ni marcado antes. Solo una decisión mutua crea una coincidencia. El servidor comprueba que quien abre un chat o envía un mensaje pertenezca a esa coincidencia.

### Ayuda administrativa

Los usuarios crean solicitudes desde Ayuda. Solo `admin` puede responderlas o cerrarlas. La cuenta administradora no participa en el descubrimiento ni en los chats.

### Fotos

Las imágenes de ejemplo pertenecen a `public/assets/images/perfiles`. Las fotos cargadas por usuarios no se incluyen en Git; en Laravel se almacenan en el disco público y se sirven mediante `php artisan storage:link`.

## Cambios seguros

- No uses `migrate:fresh` ni `db:wipe` sobre una base que tenga datos reales.
- No subas `.env`, claves, respaldos SQL, conversaciones, correos ni fotos privadas.
- Si cambias una tabla, crea una migración nueva; no edites una migración que ya se aplicó en producción.
- Si cambias una ruta protegida, conserva las pruebas de rol y autorización.
