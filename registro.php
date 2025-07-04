<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = $_POST['rol']; 
    $telefono = $_POST['telefono'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $email = $_POST['email'];

    $queryCheckEmail = "SELECT * FROM usuarios WHERE email = ?";
    $stmtCheckEmail = $conn->prepare($queryCheckEmail);
    $stmtCheckEmail->bind_param('s', $email);
    $stmtCheckEmail->execute();
    $resultCheckEmail = $stmtCheckEmail->get_result();

    if ($resultCheckEmail->num_rows > 0) {
        echo "<div class='alert alert-danger' role='alert'>El correo electrónico ya está registrado.</div>";
    } else {

        $query = "INSERT INTO usuarios (nombre_usuario, contrasena, rol, telefono, nombre_cliente, email, fecha_registro, estado) VALUES (?, ?, ?, ?, ?, ?, CURRENT_DATE, 'activo')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssss', $nombre_usuario, $contrasena, $rol, $telefono, $nombre_cliente, $email);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Usuario registrado correctamente.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error al registrar el usuario.</div>";
        }
    }
}

function verificarEstadoCuenta($email) {
    include 'db_connect.php';
    
    $query = "SELECT fecha_registro, estado, rol FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    
    $fechaRegistro = new DateTime($usuario['fecha_registro']);
    $fechaActual = new DateTime();
    $intervalo = $fechaRegistro->diff($fechaActual);
    
        echo "Fecha de registro: " . $usuario['fecha_registro'] . "<br>";
    echo "Fecha actual: " . $fechaActual->format('Y-m-d') . "<br>";
    echo "Años transcurridos: " . $intervalo->y . "<br>";
    echo "Estado actual: " . $usuario['estado'] . "<br>";

  
if ($usuario['rol'] === 'cliente') {
       
        if ($intervalo->y >= 1 && $usuario['estado'] === 'activo') {
            
            $queryUpdate = "UPDATE usuarios SET estado = 'inactivo' WHERE email = ?";
            $stmtUpdate = $conn->prepare($queryUpdate);
            $stmtUpdate->bind_param('s', $email);
            $stmtUpdate->execute();
            return 'inactivo';
        }
    }

    return $usuario['estado']; 
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
   
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="../img/internacional.png">
    <style>
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
        }

        .botones {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container form-container">
        <h1 class="text-center">Registrar Usuario</h1> <hr>
        <form method="post">
            <div class="form-group">
                <label for="rol">Rol</label>
                <select name="rol" id="rol" class="form-control">
                    <option value="cliente">Cliente</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nombre_cliente">Nombre</label>
                <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" placeholder="Nombre Completo o Razon Social" required>
            </div>
            <div class="form-group">
                <label for="telefono">Número de Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Número de Teléfono" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Correo Electrónico" required>
            </div>
            <div class="form-group">
                <label for="nombre_usuario">Username</label>
                <input type="text" name="nombre_usuario" id="nombre_usuario" class="form-control" placeholder="Nombre de Usuario" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </form>
    </div>

    <div class="botones">
        <button onclick="window.location.href='index.php';" class="btn btn-secondary">Volver</button>
        <button onclick="window.location.href='ver_usuarios.php';" class="btn btn-danger btn-sm">Ver todos los usuarios registrados</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>