<?php
session_start();
include 'db_connect.php';

// Función para verificar el estado de la cuenta
function verificarEstadoCuenta($email) {
    global $conn;
    $query = "SELECT fecha_registro, estado FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario) {
        $fecha_registro = new DateTime($usuario['fecha_registro']);
        $hoy = new DateTime();
        $intervalo = $hoy->diff($fecha_registro);

       if ($intervalo->y >= 1 && $usuario['estado'] === 'activo') {
            // Cambiar el estado a inactivo si han pasado 6 meses
            $queryUpdate = "UPDATE usuarios SET estado = 'inactivo' WHERE email = ?";
            $stmtUpdate = $conn->prepare($queryUpdate);
            $stmtUpdate->bind_param('s', $email);
            $stmtUpdate->execute();
            return 'inactivo';
        } else {
            return $usuario['estado'];
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    $query = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario) {
        // Verificar el estado de la cuenta del usuario
        $estadoCuenta = verificarEstadoCuenta($usuario['email']);

        if ($estadoCuenta === 'inactivo') {
            echo "<div class='alert alert-danger' class='text-center' role='alert'>Cuenta inactiva.</div>";
        } else {
            // Verifica si el usuario existe y la contraseña es correcta
            if (password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                $_SESSION['rol'] = $usuario['rol'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Credenciales incorrectas.";
            }
        }
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>

<header>
        <h1 class="main-title">
            SISTEMA DE VISUALIZACIÓN Y ASIGNACIÓN DE LIBROS A USUARIO
        </h1>
    </header>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center">Ingreso al Sitema</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="nombre_usuario">Nombre de Usuario</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" placeholder="Nombre de Usuario" required>
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Entrar al Sistema</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>