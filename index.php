<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db_connect.php';
$conn->set_charset("utf8mb4");
header('Content-Type: text/html; charset=UTF-8');


if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$rol = $_SESSION['rol'];  
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
  
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" href="../img/internacional.png">
  
    <link href="styles.css" rel="stylesheet">
</head>
<style></style>

<body>


    <div class="container-index">
        <h1 class="mt-4 mb-4">Bienvenido, <?php echo isset($_SESSION['nombre_usuario']) ? htmlspecialchars($_SESSION['nombre_usuario']) : 'Invitado'; ?>!</h1>

        <?php if ($rol === 'admin'): ?>
            <div class="admin">
                <h2>Opciones de Administrador</h2>
                <hr>
                <div class="opciones-container">
                
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <a href="registro.php" class="btn btn-primary btn-block">Registrar Usuario</a>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <a href="asignar_libros.php" class="btn btn-primary btn-block">Asignar Libros a Clientes</a>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <a href="admin.php" class="btn btn-primary btn-block">Subir Publicación</a>
                        </div>
                    </div>
                </div>
            </div>
          
        <?php elseif ($rol === 'cliente'): ?>
            <div class="cliente">
                <hr>
                <?php
                $usuario_id = $_SESSION['usuario_id'];
                $query = "SELECT l.id, l.titulo, l.contenido, l.pdf_url, l.portada_url 
FROM libros l
JOIN cliente_libros cl ON l.id = cl.libro_id
WHERE cl.cliente_id = ?";
                $stmt = $conn->prepare($query);
                $conn->set_charset("utf8mb4");
                $stmt->bind_param('i', $usuario_id);
                $stmt->execute();
                $result = $stmt->get_result();

                ?>

              
                <div class="books-container">
                    <?php while ($libro = $result->fetch_assoc()): ?>
                        <div class="book-card">
                            <?php if ($libro['portada_url']): ?>
                                <td><img src="<?= $libro['portada_url']; ?>" class="book-cover"></td>
                            <?php endif; ?>
                            <h5><?php echo htmlspecialchars($libro['titulo']); ?></h5>
                            <p><?php echo htmlspecialchars($libro['contenido']); ?></p>
                            <?php if ($libro['pdf_url']): ?>
                                <?php
                                $id_libro = $libro['id'];
                                ?>
                              <a href="ver_libro.php?id=<?php echo urlencode($libro['id']); ?>" class="acquire-button" target="_blank">Ver Libro</a>

                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>

            </div>
        <?php endif; ?>

        <div class="logout">
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
            <a href="cambiar_contrasena.php" class="btn btn-primary">Cambiar Contraseña</a>

        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.userway.org/widget.js" data-account="XgmqosfNZB"></script>
</body>

</html>