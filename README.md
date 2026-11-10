# Sistema WebRonda
### _Soft para trazado de rondas con control de usuario_

<img src="https://www.appm.com.ar/extra/imagenes_firma/grupotic.png" width="120" />

[![Build Status](https://github.com/jcontreras1/web-ronda?branch=master)](https://github.com/jcontreras1/web-ronda)

Web-Ronda es un sistema de gestion de rondas de control desarrollado con Laravel 13 y PHP 8.3 o superior.

- Blade para manejo de templates
- Inertia + Vue 3 para Rondas y Vue 3 para Tareas
- jQuery para interacción con las pantallas Blade
- Bootstrap 5.3.x (con sus íconos) - Como framework de frontend
- Vite para compilar los recursos del frontend
- Sanctum como librería de autentificación API

### Navegacion de Rondas

El listado y el detalle de Rondas usan Inertia + Vue 3. La navegacion entre
estas pantallas y los formularios de crear, cerrar, eliminar y registrar un
punto no recargan toda la pagina. Las demas secciones siguen usando Blade;
entrar o salir de ellas implica una navegacion normal.

El historico se consulta en paginas de 25 rondas y permite buscar por persona
o circuito y ordenar por persona o fecha en el servidor. El listado recibe contadores de puntos, novedades
y fotos, no todas sus relaciones. Vue de Tareas y Leaflet se cargan solo
cuando se necesitan. Las fotos del detalle se cargan de forma diferida y se
amplian en un lightbox fuera del mapa, con cierre por Escape, boton o fondo,
sin perder la posicion de scroll. Las fotos nuevas se reducen antes de subir.

En una ronda abierta se solicita ubicacion de alta precision con
`watchPosition`, sin reutilizar posiciones cacheadas. El marcador pulsante
y el circulo muestran el seguimiento y su margen de error estimado.
"Guardar posicion" fija las coordenadas para la novedad y detiene el GPS;
"Aceptar" envia esa posicion, descripcion y foto. "Actualizar posicion"
permite volver a seguir la ubicacion. Despues de agregar una novedad se
inicia el seguimiento para el proximo punto. El seguimiento se cancela al
salir de la pantalla o cerrar la ronda. Una lectura de mas de 10 segundos
se renueva antes de fijarla. Requiere HTTPS (o localhost) y permiso del usuario;
el navegador y el dispositivo deciden la fuente de ubicacion y su precision.
Los avisos de exito son pequenos, no desplazan el contenido y se ocultan
automaticamente despues de 3 segundos; "Novedad agregada" se oculta al segundo.

La primera visita descarga los recursos; las siguientes reutilizan el runtime.
Inertia no elimina la latencia de red, del servidor ni de los mosaicos del mapa.
Para desplegar esta migracion ejecutar `composer install`, `npm ci` y
`npm run build`, y actualizar las caches de Laravel segun el despliegue.
No requiere nuevas migraciones de base de datos ni un servidor SSR.

### Funcionalidades

- Gestión de usuarios
- Gestión de Rondas
- Georeferenciación

### Mejoras a Futuro

- Posibilidad de arrastrar el marker
- Definir geofences

# Instalar proyecto 

### Requisitos:

- PHP 8.3 o superior, tanto en CLI como en el servidor web
- Apache 2.4 (con mod_rewrite) o Nginx, con la raiz publica apuntando a `public`
- MySQL
- Composer 2.2 o superior
- Node.js 22.12 o superior y npm para compilar el frontend

### Extensiones de PHP:

- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- Filter PHP Extension
- GD PHP Extension (procesamiento de fotos)
- Exif PHP Extension (orientacion de fotos)
- Hash PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Session PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- MySQLI PHP Extension
- PDO_MYSQL PHP Extension
- DOM, XMLWriter y PDO_SQLite PHP Extensions para las pruebas

_Para entornos de desarrollo:_
```sh
git clone https://github.com/jcontreras1/web-ronda
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm ci
npm run build
```

Configurar la conexion de base de datos y el correo en `.env` antes de migrar.
El seeder inicial crea un administrador de ejemplo: cambiar su clave antes
de exponer la aplicacion.

### Actualizar una instalacion existente a Laravel 13

Respaldar la base de datos, `.env` y los archivos de `storage` antes del
despliegue. Verificar que PHP CLI y PHP de Apache/Nginx cumplan los requisitos.
Instalar las versiones fijadas en `composer.lock`, sin ejecutar `composer update`
en produccion:

```sh
php artisan down
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan migrate --force
npm ci
npm run build
php artisan storage:link
php artisan optimize
php artisan queue:restart
php artisan up
```

Ejecutar `storage:link` solo si el enlace aun no existe. No ejecutar el seeder
en una actualizacion: esta migracion de framework no agrega tablas ni modifica
datos; `migrate --force` aplica unicamente las migraciones pendientes de la
instalacion.

Se conserva la estructura de kernels y providers existente, soportada por
Laravel 13, junto con las rutas web/API, Sanctum, Blade, Inertia y Vue.
La proteccion web y la API stateful de Sanctum usan `PreventRequestForgery`:
acepta el origen `same-origin` y mantiene los tokens CSRF de formularios y Axios
para navegadores sin Fetch Metadata.

Para no invalidar sesiones activas, se mantiene `session.serialization=php`,
el nombre de la cookie y los prefijos de cache/Redis existentes. Conservar
`APP_KEY` y las variables de entorno actuales; no ejecutar `key:generate`
durante una actualizacion. La cache limita la deserializacion a datos sin
objetos (`serializable_classes=false`). Tinker usa la version 3 y las
herramientas de desarrollo usan Debugbar 4, Pest 4 y PHPUnit 12; Pest ya no
se instala en produccion.

En produccion, configurar `APP_ENV=production` y `APP_DEBUG=false`, y dar
permiso de escritura al usuario del servidor sobre `storage` y `bootstrap/cache`.
El DocumentRoot debe ser el directorio `public` del proyecto; no copiar sus
archivos a otra carpeta ni exponer la raiz del repositorio.

### Pruebas

```sh
composer install
php artisan test
node --test tests/frontend/*.test.mjs
npm run build
```

PHPUnit usa SQLite en memoria, sin modificar la base de datos de `.env`.
La cobertura incluye rondas y fotos, autenticacion web y Sanctum, recuperacion
de clave, usuarios, areas, circuitos, geofences, pantallas Blade, API de tareas
y proteccion CSRF/origen de Laravel 13.

## License

APACHE2