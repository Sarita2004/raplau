<?php
require 'db.php';
include 'navbar.php';

// Obtener datos actuales del proveedor
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT nombre, direccion, telefono FROM proveedores WHERE id_prov = :id");
    $stmt->execute([':id' => $id]);
    $proveedor = $stmt->fetch();
    if (!$proveedor) {
        die("Proveedor no encontrado.");
    }
}

// Manejar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    try {
        $stmt = $pdo->prepare("UPDATE proveedores SET nombre = :nombre, direccion = :direccion, telefono = :telefono WHERE id_prov = :id");
        $stmt->execute([
            ':nombre' => $nombre,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':id' => $id
        ]);
        $success = "Proveedor actualizado correctamente.";
    } catch (PDOException $e) {
        $error = "Error al actualizar el proveedor: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color:rgb(40, 175, 58);
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
        }

        .edit {
            background-color: #007BFF;
        }

        .delete {
            background-color: #FF0000;
        }
    </style>
    <title>Editar Proveedor</title>
</head>
<body>
    <div class="form-container">
        <h1>Editar Proveedor</h1>
        <form method="POST">
            <input type="text" name="nombre" value="<?= htmlspecialchars($proveedor['nombre']) ?>" placeholder="Nombre del Proveedor" required>
            <input type="text" name="direccion" value="<?= htmlspecialchars($proveedor['direccion']) ?>" placeholder="Dirección" required>
            <input type="tel" name="telefono" value="<?= htmlspecialchars($proveedor['telefono']) ?>" placeholder="Teléfono" required>
            <button type="submit">Guardar Cambios</button>
        </form>
        <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
        <a href="proveedores.php" class="back-button">Volver a la Lista de Proveedores</a>
    </div>
</body>
</html>
