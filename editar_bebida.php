<?php
require 'db.php';

// Obtener datos de la bebida actual
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("
        SELECT nombre, id_categoria, id_presentacion, stock_minimo
        FROM bebidas
        WHERE id_bebida = :id
    ");
    $stmt->execute([':id' => $id]);
    $bebida = $stmt->fetch();
    if (!$bebida) {
        die("Bebida no encontrada.");
    }
}

// Manejar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $presentacion = $_POST['presentacion'];
    $stock_minimo = $_POST['stock_minimo'];

    try {
        $stmt = $pdo->prepare("
            UPDATE bebidas
            SET nombre = :nombre, id_categoria = :categoria, id_presentacion = :presentacion, stock_minimo = :stock_minimo
            WHERE id_bebida = :id
        ");
        $stmt->execute([
            ':nombre' => $nombre,
            ':categoria' => $categoria,
            ':presentacion' => $presentacion,
            ':stock_minimo' => $stock_minimo,
            ':id' => $id
        ]);
        $success = "Bebida actualizada correctamente.";
    } catch (PDOException $e) {
        $error = "Error al actualizar la bebida: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Editar Bebida</title>
</head>
<body>
    <div class="form-container">
        <h1>Editar Bebida</h1>
        <form method="POST">
            <input type="text" name="nombre" value="<?= htmlspecialchars($bebida['nombre']) ?>" placeholder="Nombre de la Bebida" required>

            <!-- Selección de Categoría -->
            <select name="categoria" required>
                <?php
                $categorias = $pdo->query("SELECT * FROM categorias");
                while ($cat = $categorias->fetch()):
                ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= $cat['id_categoria'] == $bebida['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <!-- Selección de Presentación -->
            <select name="presentacion" required>
                <?php
                $presentaciones = $pdo->query("SELECT * FROM presentaciones");
                while ($pres = $presentaciones->fetch()):
                ?>
                    <option value="<?= $pres['id_presentacion'] ?>" <?= $pres['id_presentacion'] == $bebida['id_presentacion'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($pres['descripcion']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input type="number" name="stock_minimo" value="<?= $bebida['stock_minimo'] ?>" placeholder="Stock mínimo" min="0" required>
            <button type="submit">Guardar Cambios</button>
        </form>
        <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
        <a href="bebidas.php" class="back-button">Volver a la Lista de Bebidas</a>
    </div>
</body>
</html>
