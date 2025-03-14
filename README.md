# Sistema de Login con PHP y JavaScript

Este proyecto implementa un sistema de login con validación de usuario y contraseña, utilizando PHP para el backend y JavaScript para el frontend. La aplicación sigue un patrón MVC básico.

## Características

- Validación de formulario en tiempo real
- Validación en el servidor
- Persistencia de sesión con PHP Sessions
- Respaldo en localStorage
- Diseño responsive y moderno
- Efecto de carga durante la autenticación
- Cierre de sesión
- Estructura MVC

## Instalación

1. Clonar o descargar este repositorio
2. Configurar la base de datos:
   - Crear una base de datos en MySQL
   - Importar el archivo `db_setup.sql` para crear las tablas necesarias
   - Editar el archivo `config/database.php` con tus credenciales de MySQL

3. Colocar los archivos en tu servidor web
4. Acceder a la aplicación desde tu navegador

localhost/proyectolocalstorage/

## Estructura del Proyecto

```
├── api/
│   ├── check_session.php
│   ├── login.php
│   └── logout.php
│   └── create_user.php
├── assets/
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   |   └── login.js
│   └── img/
│       └── fondo.jpg
├── config/
│   └── database.php
├── controllers/
│   └── AuthController.php
├── models/
│   └── User.php
├── db_setup.sql
├── index.html
└── README.md
```

## Uso

### Credenciales de prueba
Cuando se inicia el sistema Automatica hace un post para crear el siguiente Usuario de Prueba
- Email: user1@example.com
- Contraseña: 123456


## Seguridad

El sistema implementa varias capas de seguridad:
- Contraseñas hasheadas con password_hash() y password_verify()
- Prevención de inyección SQL con consultas preparadas
- Validación de datos tanto en cliente como en servidor
- Control de sesiones en PHP

