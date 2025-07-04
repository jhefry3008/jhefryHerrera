<?php
session_start();
include 'db_connect.php';

if ($_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}


if (isset($_POST['eliminar_usuario_id'])) {
    $usuario_id = $_POST['eliminar_usuario_id'];

    $queryEliminarLibros = "DELETE FROM cliente_libros WHERE cliente_id = ?";
    $stmtEliminarLibros = $conn->prepare($queryEliminarLibros);
    $stmtEliminarLibros->bind_param('i', $usuario_id);

    if ($stmtEliminarLibros->execute()) {
        $queryEliminarUsuario = "DELETE FROM usuarios WHERE id = ?";
        $stmtEliminarUsuario = $conn->prepare($queryEliminarUsuario); 
        $stmtEliminarUsuario->bind_param('i', $usuario_id); 

        if ($stmtEliminarUsuario->execute()) {
            echo "<div class='alert alert-success' role='alert'>Usuario y libros eliminados correctamente.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error al eliminar el usuario.</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al eliminar los libros del usuario.</div>";
    }
}


$queryUsuarios = "SELECT id, nombre_usuario, rol, nombre_cliente, telefono, email FROM usuarios";
$resultUsuarios = $conn->query($queryUsuarios);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Usuarios</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../img/internacional.png">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .table-container {
            margin-top: 20px;
        }

        .botones {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="my-4" style="text-align: center;">Usuarios Registrados</h1> <hr>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Username</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['nombre_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="eliminar_usuario_id" value="<?php echo $usuario['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Quitar Usuario</button>
                                    </form>
                                    <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-primary btn-sm">Modificar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="botones text-center">
            <a href="index.php" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>