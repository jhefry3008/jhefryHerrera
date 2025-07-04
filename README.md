# 📚 Visor de Libros para Clientes

Sistema web que permite a una empresa **gestionar y mostrar libros digitales** a sus clientes registrados.  
Los administradores pueden cargar libros en formato PDF con su respectiva portada, y cada cliente solo visualiza los libros asignados a su cuenta.

---

## 🚀 Funcionalidades

### 👨‍💼 Panel de Administrador
- Crear, editar y eliminar libros
- Subir archivos PDF y portadas
- Asignar libros a clientes (funcionalidad disponible o en desarrollo)
- Gestión segura de usuarios

### 👤 Panel de Cliente
- Acceso seguro mediante login
- Visualiza solo los libros asignados
- Vista moderna con portadas, descripciones y acceso al visor PDF

---

## 🛠️ Tecnologías utilizadas

- **Backend:** PHP 7.4+
- **Base de datos:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, Bootstrap 4, JavaScript

---

## ⚙️ Requisitos para ejecutar

- Servidor local (XAMPP, MAMP, etc.)
- Base de datos con las tablas `usuarios`, `libros`, `cliente_libros`
- PHP habilitado con soporte para sesiones y manejo de archivos

---

## 🧪 Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/jhefry3008/jhefryHerrera.git
2. Copia la carpeta al directorio htdocs de XAMPP.

3. Crea la base de datos e importa el archivo .sql incluido.

4. Configura db_connect.php con tus credenciales de conexión.

5. Abre http://localhost/visor/index.php en tu navegador.

🔐 Credenciales de prueba
👤 Clientes
Usuario: clien | Contraseña: 654321

Usuario: client2 | Contraseña: 654321

👨‍💼 Administrador
Usuario: admin | Contraseña: 123456

📌 Nota
Este proyecto fue desarrollado como parte de mi formación práctica para demostrar habilidades en desarrollo web fullstack, manejo de sesiones, control de acceso, carga de archivos y relaciones entre usuarios y contenidos.
