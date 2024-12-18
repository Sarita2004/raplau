<?php
require 'db.php';
include 'navbar.php';

// Manejar eliminación
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM bebidas WHERE id_bebida = :id");
        $stmt->execute([':id' => $deleteId]);
        $success = "Bebida eliminada correctamente.";
    } catch (PDOException $e) {
        $error = "Error al eliminar la bebida: " . $e->getMessage();
    }
}

// Obtener lista de bebidas con sus categorías y presentaciones
$query = "
    SELECT b.id_bebida, b.nombre AS bebida, c.nombre AS categoria, p.descripcion AS presentacion, b.stock_minimo
    FROM bebidas b
    JOIN categorias c ON b.id_categoria = c.id_categoria
    JOIN presentaciones p ON b.id_presentacion = p.id_presentacion
";
$bebidas = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Bebidas</title>
    <link rel="stylesheet" href="styles.css">
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
            background-color:rgb(18, 156, 36);
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
    <h1>Gestión de Bebidas</h1>

    <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Presentación</th>
                <th>Stock Mínimo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $bebidas->fetch()): ?>
            <tr>
                <td><?= $row['id_bebida'] ?></td>
                <td><?= htmlspecialchars($row['bebida']) ?></td>
                <td><?= htmlspecialchars($row['categoria']) ?></td>
                <td><?= htmlspecialchars($row['presentacion']) ?></td>
                <td><?= $row['stock_minimo'] ?></td>
                <td class="actions">
                    <a href="editar_bebida.php?id=<?= $row['id_bebida'] ?>" class="edit">Editar</a>
                    <a href="?delete_id=<?= $row['id_bebida'] ?>" class="delete" onclick="return confirm('¿Estás seguro de eliminar esta bebida?');">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
 
</body>
</html>
