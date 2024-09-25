# Proyecto citas médicas

Proyecto para evaluación al puesto desarrollador web backend

## Requisitos de instalación
- [Docker](https://www.docker.com/)

## Comenzando 🚀

Clonar el repositorio con git clone.
```
git clone https://github.com/uliseseduardo148/schedules.git
```

### Instalación 🔧

Qué cosas necesitas para instalar el software y como instalarlas:
En la raíz del proyecto ejecutar
```
./startup.sh up
```

Duplicar el archivo .env.example y agregar las variables
```
DB_CONNECTION=mysql
DB_HOST=schedule-database
DB_PORT=3306
DB_DATABASE=schedule
DB_USERNAME=schedule
DB_PASSWORD=schedule
```

Enseguida entrar al docker y ejecutar
```
docker exec -it schedule-api bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
```
Con esto se tendría el proyecto funcionando

## Autores ✒️
* **Ulises Eduardo López Hernández**

"# schedule_project"