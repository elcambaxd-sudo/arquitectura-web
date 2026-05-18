# Estudio Raiz - Arquitectura Web

Web profesional para un estudio de arquitectura, urbanismo e interiorismo, construida con PHP 8+, MySQL/MariaDB, HTML5, CSS moderno y JavaScript.

## Estructura

```txt
arquitectura-web/
  admin/                 Panel de administracion
  assets/css             Estilos
  assets/js              Interacciones
  assets/img             Placeholders
  assets/uploads         Imagenes subidas
  includes               Configuracion, PDO, seguridad y layout
  public                 Web publica
  database/schema.sql    Base de datos completa con datos iniciales
```

## Instalacion local en XAMPP o WAMP

1. Instala XAMPP o WAMP.
2. Copia la carpeta `arquitectura-web` en:
   - XAMPP: `C:\xampp\htdocs`
   - WAMP: `D:\wamp64\www`
3. Inicia Apache y MySQL desde el panel de XAMPP/WAMP.
4. Abre phpMyAdmin:
   `http://localhost/phpmyadmin`
5. Importa el archivo:
   `database/schema.sql`
6. Revisa la conexion en:
   `includes/config.php`

   ```php
   DB_HOST = localhost
   DB_NAME = arquitectura_web
   DB_USER = root
   DB_PASS = ""
   BASE_URL = /arquitectura-web
   ```

7. Abre la web:
   `http://localhost/arquitectura-web`

   Alternativa funcional:
   `http://localhost/arquitectura-web/public`

8. Abre el panel:
   `http://localhost/arquitectura-web/admin`

9. Inicia sesion con:
   - Email: `admin@demo.com`
   - Password: `Admin123456`

10. Cambia la contraseña inicial despues del primer ingreso.

## URLs

Con `mod_rewrite` activo:

- `/proyectos`
- `/proyectos/casa-ladera-clara`
- `/nosotros`
- `/servicios`
- `/equipo`
- `/metodo`
- `/media`
- `/media/habitar-con-menos-ruido`
- `/contacto`
- `/admin`

Version con parametros si el rewrite no esta activo:

- `public/proyecto.php?slug=casa-ladera-clara`
- `public/articulo.php?slug=habitar-con-menos-ruido`

## Panel de administracion

El panel incluye:

- Login y cierre de sesion.
- Dashboard con resumen.
- CRUD de proyectos.
- CRUD de categorias.
- CRUD de servicios.
- CRUD de equipo.
- CRUD de articulos.
- Vista de mensajes del formulario.
- Configuracion basica del sitio.

Seguridad implementada:

- Sesiones PHP.
- `password_hash` y `password_verify`.
- PDO con prepared statements.
- CSRF en formularios de administracion.
- `session_regenerate_id(true)` al iniciar sesion.
- Validacion y sanitizacion basica.
- Bloqueo de acceso directo a `includes` y `database` por `.htaccess`.

## Subida de imagenes

Las imagenes se guardan en:

`assets/uploads/`

Formatos permitidos:

- jpg
- jpeg
- png
- webp

Tamano maximo por defecto:

- 5 MB

Puedes modificar el limite en `includes/config.php`.

## Formulario de contacto

El formulario publico valida los campos y guarda los mensajes en `mensajes_contacto`.

La seccion para envio real de correo esta comentada en:

`public/contacto.php`

Alli puedes integrar PHPMailer, SMTP, Resend, Formspree o una API propia.

## Subir a hosting con cPanel

1. Entra al Administrador de archivos o usa FTP.
2. Sube los archivos del proyecto al hosting.
3. Si el dominio apunta a `public_html`, coloca el contenido del proyecto ahi o ajusta `BASE_URL`.
4. Crea una base de datos MySQL desde cPanel.
5. Crea un usuario de base de datos.
6. Asigna el usuario a la base con todos los permisos.
7. Importa `database/schema.sql` desde phpMyAdmin del hosting.
8. Edita `includes/config.php` con los datos reales.
9. Verifica permisos de escritura en `assets/uploads`.
10. Prueba la web publica.
11. Prueba el panel de administracion.
12. Cambia la contraseña inicial.
13. Activa SSL.
14. Revisa que las URLs amigables funcionen con `.htaccess`.

## Personalizacion

Textos principales:

- Panel admin > Configuracion

Contenido editable:

- Proyectos
- Categorias
- Servicios
- Equipo
- Articulos

Estilos:

- `assets/css/styles.css`

Interacciones:

- `assets/js/app.js`

