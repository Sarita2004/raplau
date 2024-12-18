<?php
require 'db.php';
include 'navbar.php';

// Obtener datos actuales de la sucursal
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT direccion, telefono FROM sucursales WHERE id_sucursal = :id");
    $stmt->execute([':id' => $id]);
    $sucursal = $stmt->fetch();
    if (!$sucursal) {
        die("Sucursal no encontrada.");
    }
}

// Manejar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    try {
        $stmt = $pdo->prepare("UPDATE sucursales SET direccion = :direccion, telefono = :telefono WHERE id_sucursal = :id");
        $stmt->execute([
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':id' => $id
        ]);
        $success = "Sucursal actualizada correctamente.";
    } catch (PDOException $e) {
        $error = "Error al actualizar la sucursal: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Editar Sucursal</title>
</head>
<body>
    <div class="form-container">
        <h1>Editar Sucursal</h1>
        <form method="POST">
            <input type="text" name="direccion" value="<?= htmlspecialchars($sucursal['direccion']) ?>" placeholder="Dirección" required>
            <input type="tel" name="telefono" value="<?= htmlspecialchars($sucursal['telefono']) ?>" placeholder="Teléfono" required>
            <button type="submit">Guardar Cambios</button>
        </form>
        <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
        <a href="sucursal.php" class="back-button">Volver a la Lista de Sucursales</a>
    </div>
</body>
</html>
