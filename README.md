# Sistema de Embarques
### _Soft de gestión de embarques_

<img src="https://embarques.com.ar/assets/img/offiweb.png" width="120" />

[![Build Status](https://github.com/jcontreras1/embarques?branch=main)](https://github.com/jcontreras1/embarques)

Embarques es un sistema de gestión de ventas y reservas de pasajes para avistajes o salidas marítimas. Programado enteramente en PHP con el framework Laravel en su versión 8.x (última).

- Blade para manejo de templates
- jQuery para interacción con Navegador
- Bootstrap 5.1.x (con sus íconos) - Como framework de frontend
- Sanctum como librería de autentificación API
- ✨Magia del programador ✨

## Funcionalidades

- Crear salidas masivas
- Vender sobre cada salida
- Ver y editar roles
- Gestión de personal (con distintas habilidades)
- Crear reservas
- Gestion de agencias
- Vista y rendición de Vouchers de agencias
- Gestión de tarifas
- Gestión de la caja diaria, incorporando IVA compras/ventas
- Visualización estadística y generar archivos estadísticos para Provincia

## Mejoras a Futuro

- Posibilidad de venta/reserva desde la propia web
- Conexión a controladores/impresores fiscales
- Proyecciones económicas

Instalar proyecto (asumiendo que php con sus extensiones, y apache están instalados. Además de haber agregado las claves publicas al listado de admitidas por GitHub):

_Para entornos de desarrollo:_
```sh
git clone git@github.com:jcontreras1/embarques.git
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
```

Para entornos de producción, hasta migraciones es lo mismo. Luego:

```sh
npm run prod
php artisan optimize
php artisan config:cache
php artisan route:cache
```

## License

APACHE2
