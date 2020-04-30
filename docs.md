# Documentación de desarrollo del modulo de administración de usuarios y otras cosas


## Desplegar la aplicación en Servidor 

Para desplegar la aplicación se debe correr primero el programa, `/db_operations/register_earliest_users.py` y la aplicación apuntando a las bases de datos como los respaldos, al final de este documento.

## Registro de usuarios en batch

Carpeta: `/db_operations/`

Archivos:

* `db_diff.sql` (SQL): Archivo que describe las modificaciones que se hicieron en la estructura de la base de datos.
* `register_all_analyst.py` (Python3): Archivo para subir todos los usuarios que están en la tabla "analistas" en la base de datos de `metadatos`.
* `register_earliest_users.py` (Python): Archivo para subir los usuarios primeros usuarios a auth0. Este lee los usuarios del archivo `/db_operations/usuarios_iniciales.xlsx`.

## Administración de usuarios

Carpeta: `/`

Archivos:

* `registrar_administrador.php` (PHP): Programa que registra y da el rol de administración tanto en base de datos como en auth0 un usuario, esto automaticamente da acceso.
* `registrar_analista.php` (PHP): Programa que registra y da el rol de analista tanto en base de datos como en auth0 un usuario, esto automaticamente da acceso.
* `registrar_analista.php` (PHP): Programa que registra y da el rol de capturista tanto en base de datos como en auth0 un usuario, esto automaticamente da acceso.
* `borrar_usuario.php` (PHP): Programa para borrar usuario, lo borra de auth0, pero lo inactiva en la base de datos se sigue teniendo registro de él pero ya no puede desarrollar las funciones que tenía.

### Configuración Auth0

Carpeta: `/`

Archivos:

* `auth0_credentials.txt`(Texto): Viene usuario y contraseña de usuario de la cuenta de auth0 a la que está conectada esta aplicación.
* `auth0_conf.json` (JSON): Archivo de configuración que necesita auth0 para conectar con esta aplicación.
* `public/js/app.js`(Javascript): Script que gestiona la autenticación de los usuarios con auth0.

## Taxonomía

Carpeta: `/`

Archivos: 

* `taxonomia.php` (PHP): Archivo de conexión de la aplicación de metadatos con el microservicio de catalogo de datos.
* `taxonomia_2.php` (PHP): Archivo de conexión de la aplicación de metadatos con el microservicio de catalogo de datos.

## Otros desarrollos

Carpeta: `/php/`

Archivos:

* `reabrir.php` (PHP): Programa para reabrir metadatos.
* `cerrar.php` (PHP): Programa para cerrar metadatos.

## Backup inicial de la base de datos

Servidor: `ssig0.conabio.gob.mx`
Carpeta: `/home/gmagallanes/backup_metadatos/`

Archivos:

* `catalogos.tar.gz` (Comprimido de SQL): Dump de la base de datos de catalogos.  
* `metadatos.tar.gz` (Comprimido de SQL): Dump de la base de datos de metadatos con estructura modificada.

## Author

	- Pedro R [zs10011598@gmail.com]