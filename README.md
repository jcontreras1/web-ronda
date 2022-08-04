# Sistema WebRonda
### _Soft para trazado de rondas con control de usuario_

<img src="https://www.appm.com.ar/extra/imagenes_firma/grupotic.png" width="120" />

[![Build Status](https://github.com/jcontreras1/web-ronda?branch=master)](https://github.com/jcontreras1/web-ronda)

Web-Ronda es un sistema de gestion de rondas de control. Programado enteramente en PHP con el framework Laravel en su versión 8.x (ultima)

- Blade para manejo de templates
- jQuery para interacción con Navegador
- Bootstrap 5.1.x (con sus íconos) - Como framework de frontend
- Sanctum como librería de autentificación API

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
npm run prod
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