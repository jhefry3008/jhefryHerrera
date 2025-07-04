<?php
session_start();
include 'db_connect.php';


if (!isset($_GET['id'])) {
    echo "No se ha proporcionado un ID válido.";
    exit;
}

$id_libro = intval($_GET['id']);


if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$query = "SELECT l.pdf_url 
          FROM libros l 
          JOIN cliente_libros cl ON l.id = cl.libro_id 
          WHERE cl.cliente_id = ? AND l.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $usuario_id, $id_libro);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $libro = $result->fetch_assoc();
    $pagina_url = $libro['pdf_url'];

    if (!$pagina_url) {
        echo "El enlace del libro no está disponible.";
        exit;
    }
} else {
    echo "No tienes acceso a este libro.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Libro</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<script src="https://cdn.userway.org/widget.js" data-account="XgmqosfNZB"></script>
<body>
    
    <iframe src="<?php echo htmlspecialchars($pagina_url); ?>" frameborder="0"></iframe>
</body>
</html>
