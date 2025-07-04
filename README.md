# Visor de Libros para Clientes

Este sistema web permite a una empresa gestionar y mostrar libros digitales a sus clientes registrados. Los administradores pueden subir libros en formato PDF con su respectiva portada, y los clientes pueden visualizar únicamente los libros que les han sido asignados.

## Funcionalidades

- **Panel de administrador**:
  - Crear, editar y eliminar libros
  - Subir archivo PDF y portada de cada libro
  - Asignar libros a clientes (por implementar si no lo tienes)

- **Panel de cliente**:
  - Visualiza únicamente los libros asignados
  - Acceso seguro mediante login
  - Vista moderna con portadas, descripciones y botón para abrir el PDF

## Tecnologías usadas

- PHP (versión 7.4+)
- MySQL o MariaDB
- Bootstrap 4 para el diseño
- HTML/CSS/JS

## Requisitos

- Servidor local (como XAMPP o MAMP)
- Base de datos MySQL con las tablas `usuarios`, `libros`, y `cliente_libros`
- PHP habilitado con soporte para sesiones y uploads

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/jhefry3008/jhefryHerrera.git
2. Copia la carpeta a tu directorio de XAMPP (htdocs).
3. Crea la base de datos e importa el archivo SQL si lo tienes.
4. Configura db_connect.php con tus credenciales de base de datos
5. Abre localhost/visor/index.php en tu navegador.
