# Lovematch

Lovematch es una red social de citas para personas mayores de 18 años. Está construida con Laravel 12, PHP y MariaDB. Los clientes pueden crear su perfil, descubrir personas, dar me gusta, hacer coincidencias y conversar. El administrador se dedica a responder solicitudes de ayuda.

## Requisitos

- PHP 8.2 o superior.
- Composer 2.
- MariaDB 10.3 o superior (XAMPP incluye MariaDB).
- Extensiones de PHP: `pdo_mysql`, `mbstring`, `fileinfo` y `openssl`.

No se necesita Node.js para ejecutar la aplicación.

## Instalación nueva

1. Abre una terminal en la carpeta del proyecto.
2. Instala Laravel y sus dependencias:

   ```powershell
   composer install
   ```

3. Crea la configuración local si todavía no existe:

   ```powershell
   Copy-Item .env.example .env
   ```

4. Abre `.env` y configura `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`. Crea primero esa base de datos en MariaDB.
5. Genera la clave de la aplicación:

   ```powershell
   php artisan key:generate
   ```

6. Crea las tablas y los perfiles ficticios de muestra:

   ```powershell
   php artisan migrate --seed
   ```

7. Habilita las fotos que se suban desde los perfiles:

   ```powershell
   php artisan storage:link
   ```

8. Inicia el sitio:

   ```powershell
   php artisan serve --host=127.0.0.1 --port=8000
   ```

9. Abre `http://127.0.0.1:8000`.

## Conservar la base de datos anterior

La primera migración reconoce el esquema antiguo de Lovematch: convierte los roles numéricos en `admin` y `user`, amplía el campo de contraseña y elimina la tabla `roles`. Conserva usuarios, perfiles, fotos, likes, coincidencias, mensajes y solicitudes de ayuda.

1. Haz una copia de seguridad de `sena_login` antes de cambiarla. Puedes exportarla desde phpMyAdmin o, en PowerShell, ejecutar este comando (ajusta la ruta si XAMPP está instalado en otra carpeta):

   ```powershell
   & $env:ComSpec /c '"C:\xampp\mysql\bin\mysqldump.exe" -u root sena_login > sena_login_antes_de_laravel.sql'
   ```

2. Copia a `.env` la conexión de base de datos anterior. Si las cuentas existentes usan la clave `KEY` de `app/config/config.php`, copia ese valor a `LEGACY_PASSWORD_KEY` en `.env`. No publiques ni compartas ese valor.
3. Instala las dependencias, genera `APP_KEY` y aplica la migración:

   ```powershell
   composer install
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed
   php artisan storage:link
   ```

4. Al iniciar sesión correctamente, la contraseña antigua se convierte automáticamente a bcrypt. No se cambia la contraseña de las cuentas.

La migración de conversión no se puede revertir automáticamente. Para volver atrás, detén la aplicación e importa el respaldo en la misma base de datos. En PowerShell:

```powershell
& $env:ComSpec /c '"C:\xampp\mysql\bin\mysql.exe" -u root sena_login < sena_login_antes_de_laravel.sql'
```

Ajusta la ruta de XAMPP y agrega `-p` después de `-u root` si tu usuario de MariaDB tiene contraseña. Restaurar reemplaza los datos actuales con los del respaldo; se perderán los cambios hechos después de crearlo. **No uses `php artisan migrate:fresh`, `db:wipe` ni elimines tablas en una base con datos reales.**

## Primer administrador

Al migrar la base anterior, la cuenta que ya tenía el rol de administrador conserva ese rol. En una instalación nueva, registra primero la cuenta que será administradora y asígnale el rol una sola vez desde MariaDB:

```sql
UPDATE users SET role = 'admin' WHERE email = 'tu-correo@ejemplo.com';
```

El registro público siempre crea cuentas `user`; no existe un formulario público para cambiar roles. El administrador solo accede al panel de ayuda.

## Base de datos resumida

La aplicación utiliza seis tablas:

- `users`: correo, contraseña y rol.
- `profiles`: datos visibles del perfil y foto.
- `swipes`: likes y pases.
- `matches`: coincidencias mutuas.
- `messages`: conversaciones entre coincidencias.
- `support_requests`: solicitudes respondidas por el administrador.

Los cuatro perfiles de muestra usan fotos locales y no permiten iniciar sesión. Puedes volver a ejecutar `php artisan db:seed` sin duplicarlos.

## Carpetas principales

- `app/Http/Controllers`: acciones para inicio, acceso, perfiles, descubrimiento, chats y ayuda.
- `app/Http/Middleware`: control del acceso por rol.
- `app/Models`: modelos y relaciones de las seis tablas.
- `app/Providers`: autenticación compatible con contraseñas anteriores.
- `resources/views`: páginas Blade y diseños compartidos.
- `routes/web.php`: direcciones y permisos de cada página.
- `database/migrations`: creación y conversión segura de tablas.
- `database/seeders`: perfiles de muestra.
- `public/assets`: estilos e imágenes locales de muestra.
- `tests/Feature`: pruebas de registro, contraseñas y funciones sociales.

La explicación de los controladores, modelos, rutas y flujos está en [docs/guia-tecnica.md](docs/guia-tecnica.md).

## Preparar y subir el proyecto a Git

Antes de subir, ejecuta las pruebas y valida los archivos:

```powershell
composer validate --no-check-publish
php artisan test
git diff --check
git status --short
```

`.env`, las dependencias, los registros de Laravel, el enlace `public/storage`, la caché de PHPUnit y las fotos subidas por usuarios están excluidos de Git. No agregues contraseñas, archivos `.sql`, copias de seguridad ni datos reales de usuarios. Revisa también que tienes permiso para publicar las fotos de muestra.

Cuando la lista de cambios esté revisada, en la rama que quieras publicar:

```powershell
git branch --show-current
git add .
git diff --cached --name-only
git commit -m "Documenta y prepara Lovematch"
git push -u origin NOMBRE_DE_TU_RAMA
```

Antes del primer `push`, configura el repositorio remoto si aún no existe:

```powershell
git remote -v
git remote add origin URL_DE_TU_REPOSITORIO
```

Reemplaza `URL_DE_TU_REPOSITORIO` y `NOMBRE_DE_TU_RAMA` con los datos de tu repositorio. Revisa de nuevo la salida de `git diff --cached --name-only`: nunca debe incluir `.env`, respaldos ni fotos privadas.
