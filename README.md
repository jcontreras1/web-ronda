# Sistema WebRonda
### _Soft para trazado de rondas con control de usuario_

<img src="https://www.appm.com.ar/extra/imagenes_firma/grupotic.png" width="120" />

[![Build Status](https://github.com/jcontreras1/web-ronda?branch=master)](https://github.com/jcontreras1/web-ronda)

Web-Ronda es un sistema de gestion de rondas de control. Programado enteramente en PHP con el framework Laravel en su versión 8.x (ultima)

- Blade para manejo de templates
- jQuery para interacción con Navegador
- Bootstrap 5.1.x (con sus íconos) - Como framework de frontend
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

- PHP 7.4.x
- Apache 2.4
- MySQL
- Composer

### Extensiones de PHP:

- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- MySQLI PHP Extension
- PDO_MYSQL PHP Extension

_Para entornos de desarrollo:_
```sh
git clone https://github.com/jcontreras1/web-ronda
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

Para entornos de producción, hasta migraciones es lo mismo. Luego:

```sh
npm run build
php artisan optimize
php artisan config:cache
php artisan route:cache
```

En entornos linux, usualmente el _git clone_ se debería ejecutar en: _/var/www_. Dentro de _/var/www_ hay una carpeta _html_ que es donde debería copiarse el **contenido** de la carpeta _public_ de lo clonado. 
Por lo que despues de haber ejecutado todos los comandos anteriores, lo siguiente es:

```sh
cp -R /var/www/web-ronda/public/* /var/www/html
mv /var/www/html/index.php.linuxenv /var/www/html/index.php
```

Para finalizar, es necesario revisar cuestiones de permisos. Si todo esto se ejecuta como root, hay que colocar los permisos adecuados en la carpeta _/var/www/html_ 

## License

APACHE2