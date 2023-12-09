# Proyecto: Foro Web

## Descripción

El proyecto consiste en una web basica de pocas paginas, donde el usuario puede crear articulos y puntuar los articulos que otros usuarios hayan publicado.
No se tiene contemplado editar un articulo ni responder al mismo en forma de comentarios.

El usuario puede registrarse en la web, logearse, crear articulos, puntuarlos, eliminarlos y editar sus propios datos de usuario.
No se contempla que el usuario pueda eliminar su cuenta.

## Tabla de Contenidos

- [Instalación](#instalación)
- [Uso](#uso)
- [Datos de Interes](#datos-de-interes)

## Instalación

Para instalar y ejecutar este proyecto, sigue estos pasos:

1. Asegúrate de tener instalado `composer` en tu sistema. Si no lo tienes, puedes descargarlo desde [aquí](https://getcomposer.org/download/).

2. Clona este repositorio en tu máquina local usando `git clone`.

3. Navega hasta el directorio del proyecto.

4. Ejecuta `composer install` para instalar las dependencias del proyecto. Esto utilizará el archivo `composer.lock` que ya está en el repositorio para instalar las versiones correctas.

5. Si es necesario, ejecuta `composer require nombre-de-la-dependencia` para cada dependencia adicional que necesites.

6. Finalmente, ejecuta `composer dump-autoload -o` para generar el directorio `vendor` y el archivo de autoload.

7. El proyecto incluye un script SQL adjunto. Utiliza este script para crear y poblar la base de datos.

8. Modifica el archivo `conexion.php` que se encuentra en el directorio de `librerias`. Deberás actualizar la configuración para que apunte a la base de datos que deseas utilizar.

Ahora deberías poder ejecutar el proyecto en tu entorno local.

## Uso

Teniendo en cuenta que se empleo el script SQL adjunto para crear y poblar la base de datos, se disponen los siguientes usuarios de prueba.

1. `prueba@gmail.com` y `profesor@gmail.com` con sus respectivas contraseñas `prueba` y `profesor`.

2. En caso de querer añadir a mano mediante SQL un nuevo usuario, tener en cuenta que las contraseñas se encriptan en ARGON2ID desde el servidor.

3. En el mismo poblado se incluyen articulos ya creados y puntuados para que se pueda probar directamente su uso.

4. Para crear un nuevo usuario se debe realizar desde el formulario de registro `/registro` y para logearse desde el formulario de login en `/login`.

5. El listado principal de todos los articulos se encuentra en `/home` mientras que el listado de articulos individual del usuario se encuentra en `/personal`.

6. Es posible ver el listado de articulos de un usuario en concreto pinchando en su nombre.

7. Para editar los datos del usuario se emplean los formularios situados en `/perfil`.

Cabe destacar que para hacer uso de cualquier pagina concerniente a los usuarios, se debe estar logeado, de otro modo el servidor redirigira al login.

## Datos de Interes

1. La web esta desplegada [aquí](https://alejandrosanchezfernandez.duckdns.org/). Sin embargo es necesario encender la maquina EC2 de amazon donde esta hosteada de momento. Contacte conmigo en caso de querer disponer de ella para que encienda la maquina.

2. La arquitectura del proyecto se basa en el patrón de diseño MVC, utilizando PDO para interactuar con la base de datos, empleado  patrones singleton...

3. También se ha hecho uso de extensiones Twig, específicamente la implementación de la interfaz `GlobalsInterface` para facilitar la manipulación y presentación de datos en las vistas.

4. El codigo en algunas partes es confuso y poco ordenado, del mismo modo hay partes que se pueden optimizar.