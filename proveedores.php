<?php
require 'db.php';
include 'navbar.php';

// Manejar eliminación
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM proveedores WHERE id_prov = :id");
        $stmt->execute([':id' => $deleteId]);
        $success = "Proveedor eliminado correctamente.";
    } catch (PDOException $e) {
        $error = "Error al eliminar el proveedor: " . $e->getMessage();
    }
}

// Obtener lista de proveedores
$proveedores = $pdo->query("SELECT id_prov, nombre, direccion, telefono FROM proveedores");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proveedores</title>
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
</head>
<body>
    <h1>Gestión de Proveedores</h1>

    <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $proveedores->fetch()): ?>
            <tr>
                <td><?= $row['id_prov'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['direccion'] ?></td>
                <td><?= $row['telefono'] ?></td>
                <td class="actions">
                    <a href="editar_proveedor.php?id=<?= $row['id_prov'] ?>" class="edit">Editar</a>
                    <a href="?delete_id=<?= $row['id_prov'] ?>" class="delete" onclick="return confirm('¿Estás seguro de eliminar este proveedor?');">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
