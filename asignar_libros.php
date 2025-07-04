<?php
session_start();
include 'db_connect.php';


if ($_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_POST['reset_cliente'])) {
    unset($_SESSION['cliente_id']);
    header("Location: asignar_libros.php");
    exit;
}


if (isset($_POST['cliente_seleccionado'])) {
    $_SESSION['cliente_id'] = $_POST['cliente_seleccionado'];
} elseif (!isset($_SESSION['cliente_id'])) {
    $_SESSION['cliente_id'] = null;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['libro_id']) && isset($_SESSION['cliente_id'])) {
    $libro_id = $_POST['libro_id'];
    $cliente_id = $_SESSION['cliente_id'];

   
    $queryAsignarLibro = "INSERT INTO cliente_libros (cliente_id, libro_id) VALUES (?, ?)";
    $stmt = $conn->prepare($queryAsignarLibro);
    $stmt->bind_param('ii', $cliente_id, $libro_id);

    if ($stmt->execute()) {
        echo "Libro asignado correctamente.";
    } else {
        echo "Error al asignar el libro.";
    }
}


if (isset($_POST['eliminar_libro_id']) && isset($_SESSION['cliente_id'])) {
    $libro_id = $_POST['eliminar_libro_id'];
    $cliente_id = $_SESSION['cliente_id'];

    
    $queryEliminarLibro = "DELETE FROM cliente_libros WHERE cliente_id = ? AND libro_id = ?";
    $stmtEliminarLibro = $conn->prepare($queryEliminarLibro);
    $stmtEliminarLibro->bind_param('ii', $cliente_id, $libro_id);

    if ($stmtEliminarLibro->execute()) {
        echo "Libro eliminado correctamente.";
    } else {
        echo "Error al eliminar el libro.";
    }
}

$queryClientes = "SELECT id, nombre_usuario FROM usuarios WHERE rol = 'cliente' ORDER BY id DESC";
$resultClientes = $conn->query($queryClientes);


$librosAsignados = [];
$clienteSeleccionadoNombre = '';

if (isset($_SESSION['cliente_id'])) {
    $cliente_id = $_SESSION['cliente_id'];


    $queryClienteSeleccionado = "SELECT nombre_usuario FROM usuarios WHERE id = ?";
    $stmtClienteSeleccionado = $conn->prepare($queryClienteSeleccionado);
    $stmtClienteSeleccionado->bind_param('i', $cliente_id);
    $stmtClienteSeleccionado->execute();
    $resultClienteSeleccionado = $stmtClienteSeleccionado->get_result();
    if ($clienteSeleccionado = $resultClienteSeleccionado->fetch_assoc()) {
        $clienteSeleccionadoNombre = $clienteSeleccionado['nombre_usuario'];
    }


    $queryLibrosAsignados = "
        SELECT l.id, l.titulo
        FROM libros l
        JOIN cliente_libros cl ON l.id = cl.libro_id
        WHERE cl.cliente_id = ?";
    $stmtLibrosAsignados = $conn->prepare($queryLibrosAsignados);
    $stmtLibrosAsignados->bind_param('i', $cliente_id);
    $stmtLibrosAsignados->execute();
    $resultLibrosAsignados = $stmtLibrosAsignados->get_result();
    $librosAsignados = $resultLibrosAsignados->fetch_all(MYSQLI_ASSOC);

    
    $queryLibrosDisponibles = "
        SELECT id, titulo 
        FROM libros 
        WHERE id NOT IN (
            SELECT libro_id 
            FROM cliente_libros 
            WHERE cliente_id = ?
        )";
    $stmtLibrosDisponibles = $conn->prepare($queryLibrosDisponibles);
    $stmtLibrosDisponibles->bind_param('i', $cliente_id);
    $stmtLibrosDisponibles->execute();
    $resultLibrosDisponibles = $stmtLibrosDisponibles->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Libros a Clientes</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="../img/internacional.png">
    <style>
        .cliente, .libros-asignados, .libros-disponibles {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Asignar y Quitar Libros a Clientes</h1> <hr class="asignar">

        <?php if (!isset($_SESSION['cliente_id'])): ?>
            <form method="post">
                <div class="form-group">
                    <label for="cliente_seleccionado">Selecciona un Cliente:</label>
                    <select name="cliente_seleccionado" id="cliente_seleccionado" class="form-control" required>
                        <option value="">Selecciona un cliente</option>
                        <?php while ($cliente = $resultClientes->fetch_assoc()): ?>
                            <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['nombre_usuario']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Marcar Cliente</button>
                <button onclick="window.location.href='index.php';" class="btn btn-secondary">Volver</button>
            </form>
        <?php else: ?>
            <h2>Cliente Seleccionado: <?php echo htmlspecialchars($clienteSeleccionadoNombre); ?></h2>

            
            <div class="libros-asignados">
                <h3>Libros Asignados:</h3>
                <ul class="list-group">
                    <?php if (count($librosAsignados) > 0): ?>
                        <?php foreach ($librosAsignados as $libro): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="color: #102b2c;" >
                                <?php echo htmlspecialchars($libro['titulo']); ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="eliminar_libro_id" value="<?php echo $libro['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Quitar Libro</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item" style="color: #102b2c;">No hay libros asignados a este cliente.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="libros-disponibles">
                <h3>Asignar Nuevo Libro:</h3>
                <form method="post">
                    <div class="form-group">
                        <label for="libro_id">Selecciona un Libro:</label>
                        <select name="libro_id" id="libro_id" class="form-control" required>
                            <option value="">Selecciona un libro</option>
                            <?php while ($libroDisponible = $resultLibrosDisponibles->fetch_assoc()): ?>
                                <option value="<?php echo $libroDisponible['id']; ?>"><?php echo htmlspecialchars($libroDisponible['titulo']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Asignar Libro</button>
                </form>
            </div>

          
            <div class="mt-4">
                <form method="post" style="display:inline;">
                    <button type="submit" class="btn btn-secondary" name="reset_cliente">Volver a Marcar Cliente</button>
                </form>
            </div>

            <?php
           
            if (isset($_POST['reset_cliente'])) {
                unset($_SESSION['cliente_id']);
                header("Location: asignar_libros.php");
                exit;
            }
            ?>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
