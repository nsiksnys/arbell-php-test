# arbell-php-test
Prueba técnica hecha en Mayo/Junio 2025

Creado con
* [Symfony 7.3](https://symfony.com)
* [Tabler 1.3.2](https://tabler.io)
* [API Platform 4.1](https://api-platform.com)

También usa
* [MariaDB](https://mariadb.org)
* [Symfony Profiler](https://symfony.com/doc/current/profiler.html)
* [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle) para autenticación con JWT (JSON Web Token)

## Ramas
Este proyecto contiene dos ramas
* la rama `web`, que contiene un ABML (alta, baja, modificaciones y lista) básico.
* la rama `rest-api`, que contiene una API REST para operaciones ABML básicas.

## Paquetes de Composer
`composer install` instala todos los paquetes necesarios.

En la rama `rest-api` el comando `php bin/console lexik:jwt:generate-keypair` genera las llaves públicas y privadas necesarisas para usar tokens para autenticación JWT.

## Detalles técnicos
* El proyecto está desarrollado en Symfony para aprovechar los roles y permisos que provee el framework.
* Los permisos se manejan mediante la entidad `Profile`. Cada uno de ellos contiene la variable `role`, que sigue la convención `ROLE_[NOMBRE]` usada para definir roles en Symfony. Éstos son creados usando migraciones. Para más información acerca de esta clase, vaya a la sección `Datos de prueba`.
* En la rama `web` los roles se manejan mediante el enum `RoleEnum` para evitar confusiones.
* La base de datos elegida es MariaDB, la versión open source de MySql. Para el caso de una base de datos MySql, sólo hace falta cambiar la url como se explica más adelante en la sección `Variables de entorno`.

### Permisos de usuario
* Sólo usuarios autenticados pueden acceder al ABLM.
* Sólo usuarios con perfil de administrador (`ROLE_ADMIN`) pueden borrar usuarios.

### Diagrama entidad relación (DER)
![Diagrama generado por phpMyAdmin](/screenshots/arbell-test%20-%20ERD.png)
Nota: doctrine_migration_versions es una tabla interana usada para el seguimiento de las migraciones.

## Variables de entorno
Todas las variables de entorno se manejan mediante un archivo `.env`.

Éste proyecto provee de un archivo `.env` de ejemplo. Symfony permite archivos de la forma `.env.[ENTORNO]`, donde se guardan variables propias de un entorno en particular.

En este caso se necesita definir las siguientes variables antes de ejecutar el proyecto por primera vez:
* `APP_ENV` para indicar qué tipo de entorno estamos usando. Por defecto es `dev`.
* `DATABASE_URL` para conectar con la base de datos. Para MariaDB de 10.11.2 en adelante la url es `mysql://[USUARIO]:[CONTRASEÑA]@[URL_BD]:[PUERTO_BD]/[NOMBRE_BD]?serverVersion=10.11.2-MariaDB&charset=utf8mb4` donde
    * `USUARIO` es un usuario válido de la base de datos, con al menos permisos de lectura y escritura
    * `CONTRASEÑA` es la contraseña de dicho usuario
    * `URL_BD` es la url usada por la base de datos. Para entornos locales, el valor por defecto es `localhost`
    * `PUERTO_BD` es el puerto usado por la base de datos. Para entornos locales, el valor por defecto es `3306`
    * `NOMBRE_BD` es el nombre de la base de datos

Para bases de datos en MySql, la la url es de la forma `mysql://[USUARIO]:[CONTRASEÑA]@[URL_BD]:[PUERTO_BD]/[NOMBRE_BD]`.

Para versiones anteriores de MariaDB u otras bases de datos, visite [Databases and the Doctrine ORM (en inglés)](https://symfony.com/doc/current/doctrine.html#configuring-the-database) en la documentación oficial de Symfony.

* Las siguientes variables corresponden al paquete `lexik/jwt-authentication-bundle` usado en la rama `rest-api`.
    * `JWT_SECRET_KEY` define la ubicación de la llave privada. El valor por defecto es `%kernel.project_dir%/config/jwt/private.pem`.
    * `JWT_PUBLIC_KEY` define la ubicación de la llave pública. El valor por defecto es `%kernel.project_dir%/config/jwt/public.pem`.
    * `JWT_PASSPHRASE` es requerido para la creación de los token. Ésta variable se agrega automáticamente al generar las llaves.

Para más información acerca de éste paquete consulte el [repositorio en GitHub](https://github.com/lexik/LexikJWTAuthenticationBundle).

## Datos de prueba
`php bin/console doctrine:database:create` para crear una base de datos vacía. Este comando fallará si el usuario definido en el archivo `.env` no tiene los pemisos para esta operación.

`php bin/console make:migrations:migrate` para crear las tablas de la base e insertar los datos de prueba.

Los datos de prueba consisten de dos perfiles, uno con rol `ROLE_ADMIN` y otro con rol `ROLE_USER`, y un usuario, `admin@example.com` perfil de administrador y contraseña `test123`.

## Entorno local
Para desarrollo en un entorno local, se puede usar el [binario de Symfony](https://symfony.com/download) para correr el servidor.

Una vez el binario está instalado, ejecute el comando `php bin/console asset-map:compile --no-debug` para compilar los assets, seguido por `symfony server:start`. Si éste último comando no funciona, hay que buscar dónde está ubicado el archivo ejecutable. Para sistemas Linux la ubicación por defecto es `$HOME/.symfony/bin`.

Por defecto el servidor está disponible en `http://localhost:8000`.

## Capturas de pantalla
### Web
#### Página principal
##### Sin iniciar sesión
![Sin iniciar sesión](/screenshots/Homepage%20-%20not%20logged%20in.png)

##### Con sesión inciada
![Con sesión inciada](/screenshots/Homepage%20-%20logged%20in.png)

#### Login
![Login](/screenshots/Log%20in.png)

#### Lista
![Lista de usuarios](/screenshots/User%20index.png)

#### Alta
![Formulario de alta](/screenshots/New%20User%20form.png)
![Mensaje después de crear un usuario](/screenshots/User%20index%20-%20user%20created.png)

#### Detalles
![Detalles de un usuario](/screenshots/User%20details.png)

#### Modificación
![Formulario de modificación](/screenshots/Edit%20User.png)
![Cambio de contraseña](/screenshots/Edit%20User%20-%20update%20password%20.png)
![Mensaje después de modificar un usuario](/screenshots/User%20index%20-%20password%20updated.png)

#### Baja
![Modal de advertencia](/screenshots/User%20index%20-%20delete%20user%20modal.png)
![Mensaje después de borrar un usuario](/screenshots/User%20index%20-%20user%20deleted.png)

#### Acceso denegado
![Acceso denegado al listado de usuarios](/screenshots/User%20list%20-%20no%20authorization.png)

#### Baja de usuario prohibida
![Baja de usuario prohibida](/screenshots/User%20delete%20-%20no%20authorization.png)

#### Mobile (iPad 810x1080)
![Mobile (iPad 810x1080)](/screenshots/User%20index%20-%20iPad.png)

### API REST (con Imsomnia)
Ejemplos disponibles en `insomnia_api_requests.yml`

#### Autenticación
![POST auth](/screenshots/Login%20request.png)

#### Configuración de método de autenticación
![Bearer token](/screenshots/User%20auth%20config.png)

#### Lista de Profile
![GET de todos los Profile](/screenshots/GET%20profiles%20request.png)

#### Lista de User
![GET de todos los User](/screenshots/GET%20users%20request.png)

#### Alta de nuevo usuario (con [Faker](https://github.com/FakerPHP/Faker))
![POST de un user](/screenshots/POST%20create%20new%20user%20with%20faker.png)

#### Consulta de un usuario
![GET de un User](/screenshots/GET%20single%20user.png)

#### Modificación de un usuario
![PATCH user](/screenshots/PATCH%20user.png)

#### Modificación de un usuario - error de validación
![PATCH de un user - error de validación](/screenshots/PATCH%20request%20error.png)

#### Baja de un usuario
![DELETE de un user](/screenshots/DELETE%20user%20ok.png)

### Permisos
##### Baja de un usuario - operación no permitida para un usuario sin perfil de administrador
![DELETE de un user - error 403](/screenshots/DELETE%20user%20-%20access%20denied.png)

##### Lista de User - acceso no permitido sin autenticación
![GET de todos los users - error 401](/screenshots/GET%20users%20request%20-%20not%20logged%20in.png)