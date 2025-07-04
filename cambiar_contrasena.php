<?php
if (isset($_GET['mensaje'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
    echo "<div class='alert alert-info' role='alert'>{$mensaje}</div>";
}

session_start();
include 'db_connect.php';


if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if (isset($_POST['cambiar_contrasena'])) {
    $contrasena_actual = $_POST['contrasena_actual'];
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    
    $query = "SELECT contrasena FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $stmt->bind_result($contrasena_hash);
    $stmt->fetch();
    $stmt->close();

  
    if (password_verify($contrasena_actual, $contrasena_hash)) {
     
        if ($nueva_contrasena === $confirmar_contrasena) {
            $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

            
            $queryActualizar = "UPDATE usuarios SET contrasena = ? WHERE id = ?";
            $stmtActualizar = $conn->prepare($queryActualizar);
            $stmtActualizar->bind_param('si', $nueva_contrasena_hash, $usuario_id);

            if ($stmtActualizar->execute()) {
               
                header("Location: login.php?mensaje=Contraseña actualizada, por favor inicie sesión de nuevo.");
                exit;
            } else {
                $error = "Error al cambiar la contraseña.";
            }
        } else {
            $error = "Las nuevas contraseñas no coinciden.";
        }
    } else {
        $error = "Contraseña actual incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="../img/internacional.png">
</head>
<body>
    <div class="container">
        <h1 class="my-4" style="text-align: center;">Cambiar Contraseña</h1> <hr>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="contrasena_actual">Contraseña Actual</label>
                <input type="password" class="form-control" id="contrasena_actual" name="contrasena_actual" required>
            </div>

            <div class="form-group">
                <label for="nueva_contrasena">Nueva Contraseña</label>
                <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
            </div>

            <div class="form-group">
                <label for="confirmar_contrasena">Confirmar Nueva Contraseña</label>
                <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
            </div>

            <button type="submit" name="cambiar_contrasena" class="btn btn-primary">Cambiar Contraseña</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
