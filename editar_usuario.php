<?php
session_start();
include 'db_connect.php';

if ($_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}


if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    $queryUsuario = "SELECT nombre_usuario, rol, email, telefono FROM usuarios WHERE id = ?";
    $stmtUsuario = $conn->prepare($queryUsuario);
    $stmtUsuario->bind_param('i', $usuario_id);
    $stmtUsuario->execute();
    $stmtUsuario->bind_result($nombre_usuario, $rol, $email, $telefono);
    $stmtUsuario->fetch();
    $stmtUsuario->close();
} else {
    header("Location: ver_usuarios.php");
    exit;
}


if (isset($_POST['actualizar_usuario'])) {
    $nombre_usuario_actualizado = $_POST['nombre_usuario'];
    $rol_actualizado = $_POST['rol'];
    $email_actualizado = $_POST['email'];
    $telefono_actualizado = $_POST['telefono'];

    $queryActualizar = "UPDATE usuarios SET nombre_usuario = ?, rol = ?, email = ?, telefono = ? WHERE id = ?";
    $stmtActualizar = $conn->prepare($queryActualizar);
    $stmtActualizar->bind_param('ssssi', $nombre_usuario_actualizado, $rol_actualizado, $email_actualizado, $telefono_actualizado, $usuario_id);

    if ($stmtActualizar->execute()) {
        echo "<div class='alert alert-success' role='alert'>Usuario actualizado correctamente.</div>";
        header("Location: ver_usuarios.php");
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al actualizar el usuario.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../img/internacional.png">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4" style="text-align: center;">Modificar Usuario</h1> <hr>

        <form method="post">
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo htmlspecialchars($nombre_usuario); ?>" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol</label>
                <select class="form-control" id="rol" name="rol">
                    <option value="admin" <?php if ($rol == 'admin') echo 'selected'; ?>>Administrador</option>
                    <option value="cliente" <?php if ($rol == 'cliente') echo 'selected'; ?>>Cliente</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>">
            </div>

            <button type="submit" name="actualizar_usuario" class="btn btn-primary">Actualizar</button>
            <a href="ver_usuarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
