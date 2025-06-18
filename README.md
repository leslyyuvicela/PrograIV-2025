# Entorno del proyecto

# Que hacer al clonar el repositorio compartido

Primero crear la base de datos en mariadb (donamiga)

TENER INSTALADO:
- Composer
- Laravel
- Node.js
- Vue CLI
- Typescript


## Comandos que hay que ejecutar al clonar el repositorio

### Abrir una terminal (Ctrl + ñ)
    cd backend
    composer install
    cp .env.example .env

#### Buscar en el explorador en backend/.env la configuracion de la base de datos (lineas 24-30) y colocar las configuaraciones de nombre de bases de datos, usuario y contraseña y puerto, luego seguir con los comandos
    php artisan key:generate
    php artisan migrate
    php artisan jwt:secret
    cd ..
    cd frontend
    npm install

# Correr los servidores
Abrir dos terminales

### Primera terminal (backend): 
    cd backend
    php artisan serve

### Segunda terminal (frontend)
    cd frontend
    npm run serve


