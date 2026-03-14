# OmniSuite - Sistema de gestión de inventario y préstamos

OmniSuite es un sistema modular desarrollado en Laravel 12 que permite gestionar herramientas, kits, préstamos, usuarios, roles y permisos. Incluye un sistema de autenticación con registro único para el primer administrador y funcionalidades móviles mediante códigos QR.

## Requisitos previos

- PHP >= 8.2
- Composer
- Node.js y npm
- MySQL >= 8.0
- Extensión de PHP: pdo_mysql, mbstring, bcmath, xml, zip

## Instalación paso a paso

### 1. Clonar el repositorio

```bash
git clone https://github.com/ballsfox14/omnisuite.git omnisuite
cd omnisuite
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Configurar el archivo de entorno

Copiar el archivo de ejemplo y configurar las credenciales de base de datos:

```bash
cp .env.example .env
```

Edita el archivo `.env` y ajusta los siguientes parámetros según tu entorno:

```env
DB_DATABASE=omnisuite
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Generar la clave de la aplicación

```bash
php artisan key:generate
```

### 5. Instalar dependencias de Node y compilar assets

```bash
npm install
npm run build
```

### 6. Ejecutar migraciones y seeders

Las migraciones crearán las tablas necesarias y el seeder creará el rol **Administrador** y todos los permisos.

```bash
php artisan migrate:fresh
php artisan db:seed --class="Modules\Admin\Database\Seeders\PermisosRolesSeeder"
```

### 7. Crear el primer usuario administrador

Puedes hacerlo de dos formas:

#### Opción A: A través del registro web (solo funciona la primera vez)

Accede a `http://localhost:8000/register` y completa el formulario. El primer usuario registrado obtendrá automáticamente el rol **Administrador**.

#### Opción B: Manualmente con Tinker

```bash
php artisan tinker
>>> $user = App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);
>>> $user->assignRole('Administrador');
>>> exit
```

### 8. Configurar el servidor web

Si usas el servidor integrado de Laravel:

```bash
php artisan serve
```

Luego accede a `http://localhost:8000` y deberías ser redirigido al login.

### 9. (Opcional) Crear áreas de ejemplo

Si deseas agregar áreas para los usuarios, ejecuta:

```bash
php artisan tinker
>>> App\Models\Area::create(['name' => 'Producción']);
>>> App\Models\Area::create(['name' => 'Transmisiones']);
>>> App\Models\Area::create(['name' => 'Mantenimiento']);
>>> exit
```

### 10. (Opcional) Ejecutar la aplicación en la red local

Para acceder a OmniSuite desde otros dispositivos en la misma red (por ejemplo, tu teléfono móvil), necesitas ejecutar el servidor de desarrollo con tu dirección IP local.

1.  Obtén tu dirección IP local:
    *   En **Windows**: Abre `cmd` y ejecuta `ipconfig`. Busca la dirección IPv4 (ej. `192.168.1.10`).
    *   En **Linux/macOS**: Abre la terminal y ejecuta `ifconfig` o `hostname -I`.

2.  Ejecuta el servidor con la IP y el puerto deseado (ej. puerto 8000):

    ```bash
    php artisan serve --host=192.168.1.10 --port=8000
    ```

    (Reemplaza `192.168.1.10` con tu IP real).

3.  Desde otros dispositivos conectados a la misma red, abre el navegador y accede a:
    `http://192.168.1.10:8000`

    Asegúrate de que el firewall permita la conexión en ese puerto.

**Nota:** Este método es solo para desarrollo/pruebas. Para entornos de producción, configura un servidor web como Nginx o Apache.

## Estructura del proyecto

- `Modules/Admin` – Gestión de usuarios, roles, permisos y áreas.
- `Modules/Inventory` – Gestión de herramientas, kits y préstamos.
- `app/Http/Controllers/MobileLoanController.php` – Controlador para préstamos rápidos desde móvil.
- `resources/views/mobile/` – Vistas optimizadas para dispositivos móviles.

## Notas importantes

- El registro web (`/register`) solo está disponible para crear el **primer administrador**. Después de eso, cualquier intento de acceso devolverá un error 403.
- Los códigos QR de los kits se generan automáticamente y se pueden descargar desde la vista de detalle.
- Los accesorios de las herramientas se guardan como un campo JSON en la tabla `tools`.

## Soporte

Para cualquier incidencia, contacta con el desarrollador.