<?php
session_start();
include 'db_connect.php';

if ($_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}


if (isset($_POST['eliminar_libro_id'])) {
    $libro_id = $_POST['eliminar_libro_id'];
    $stmtDependencias = $conn->prepare("DELETE FROM cliente_libros WHERE libro_id = ?");
if ($stmtDependencias) {
    $stmtDependencias->bind_param('i', $libro_id);
    $stmtDependencias->execute();
    $stmtDependencias->close();
} else {
    die("Error al preparar la consulta de dependencias: " . $conn->error);
}
    $stmt = $conn->prepare("DELETE FROM libros WHERE id = ?");
    $stmt->bind_param('i', $libro_id);
    $stmt->execute();
}


if (isset($_POST['editar_libro_id'])) {
    $id = $_POST['editar_libro_id'];
    $titulo = $_POST['titulo_edit'];
    $contenido = $_POST['contenido_edit'];
    $query = "UPDATE libros SET titulo = ?, contenido = ?";
    $params = [$titulo, $contenido];
    $types = "ss";

    if ($_FILES['pdf_file_edit']['error'] === UPLOAD_ERR_OK) {
        $pdf_path = 'uploads/pdf/' . basename($_FILES['pdf_file_edit']['name']);
        move_uploaded_file($_FILES['pdf_file_edit']['tmp_name'], $pdf_path);
        $query .= ", pdf_url = ?";
        $params[] = $pdf_path;
        $types .= "s";
    }

    if ($_FILES['portada_file_edit']['error'] === UPLOAD_ERR_OK) {
        $img_path = 'uploads/portadas/' . basename($_FILES['portada_file_edit']['name']);
        move_uploaded_file($_FILES['portada_file_edit']['tmp_name'], $img_path);
        $query .= ", portada_url = ?";
        $params[] = $img_path;
        $types .= "s";
    }

    $query .= " WHERE id = ?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo']) && isset($_POST['contenido'])) {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $pdf = $_FILES['pdf_file'];
    $portada = $_FILES['portada_file'];

    if ($pdf['error'] === UPLOAD_ERR_OK && $portada['error'] === UPLOAD_ERR_OK) {
        $pdf_path = 'uploads/pdf/' . basename($pdf['name']);
        $portada_path = 'uploads/portadas/' . basename($portada['name']);
        if (!is_dir('uploads/pdf')) mkdir('uploads/pdf', 0777, true);
        if (!is_dir('uploads/portadas')) mkdir('uploads/portadas', 0777, true);
        move_uploaded_file($pdf['tmp_name'], $pdf_path);
        move_uploaded_file($portada['tmp_name'], $portada_path);

        $stmt = $conn->prepare("INSERT INTO libros (titulo, contenido, pdf_url, portada_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $titulo, $contenido, $pdf_path, $portada_path);
        $stmt->execute();
    }
}

$libros = $conn->query("SELECT * FROM libros");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Libros</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="../img/internacional.png">
</head>

<body>
    <div class="container custom-container">
        <h1 class="text-center mb-4">Administrar Libros</h1> <hr>


        <div class="mb-4">
            <h2>Libros Creados</h2>
             <div class="botones text-center mb-3">
        <button class="btn btn-success" data-toggle="modal" data-target="#crearLibroModal">Crear Nuevo Libro</button>
    </div>
            <div class="table-responsive">
                <table class="table table-bordered custom-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Título</th>
                            <th>Contenido</th>
                            <th>PDF</th>
                            <th>Portada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php while ($libro = $libros->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($libro['titulo']); ?></td>
          <td><?= htmlspecialchars($libro['contenido']); ?></td>
          <td>
            <a href="<?= $libro['pdf_url']; ?>" target="_blank" class="btn btn-sm btn-primary">Ver PDF</a>
          </td>
          <td>
            <img src="<?= $libro['portada_url']; ?>">
          </td>
          <td>
            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editarModal<?= $libro['id']; ?>">Editar</button>
            <form method="post" style="display:inline;" onsubmit="return confirm('¿Eliminar este libro?');">
              <input type="hidden" name="eliminar_libro_id" value="<?= $libro['id']; ?>">
              <button class="btn btn-danger btn-sm">Quitar Libro</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
    <div class="botones text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Volver al Inio</a>
    </div>


<!-- Modal crear libro -->
<div class="modal fade" id="crearLibroModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="post" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="color: #102b2c;">Crear Nuevo Libro</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Título</label>
          <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Contenido</label>
          <textarea name="contenido" class="form-control" required></textarea>
        </div>
        <div class="form-group">
          <label>Archivo PDF</label>
          <input type="file" name="pdf_file" class="form-control-file" required>
        </div>
        <div class="form-group">
          <label>Portada</label>
          <input type="file" name="portada_file" class="form-control-file" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success">Crear Libro</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modales de edición -->
<?php
$libros->data_seek(0);
while ($libro = $libros->fetch_assoc()):
?>
<div class="modal fade" id="editarModal<?= $libro['id']; ?>" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="post" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="color: #102b2c;">Modificar Libro</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="editar_libro_id" value="<?= $libro['id']; ?>">
        <div class="form-group">
          <label>Título</label>
          <input type="text" name="titulo_edit" class="form-control" value="<?= htmlspecialchars($libro['titulo']); ?>" required>
        </div>
        <div class="form-group">
          <label>Contenido</label>
          <textarea name="contenido_edit" class="form-control" required><?= htmlspecialchars($libro['contenido']); ?></textarea>
        </div>
        <div class="form-group">
          <label>Nuevo PDF (opcional)</label>
          <input type="file" name="pdf_file_edit" class="form-control-file">
        </div>
        <div class="form-group">
          <label>Nueva portada (opcional)</label>
          <input type="file" name="portada_file_edit" class="form-control-file">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Guardar Cambios</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<?php endwhile; ?>
        

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
