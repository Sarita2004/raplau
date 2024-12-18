<?php
require 'db.php';
include 'navbar.php';

$error = '';
$success = '';

// Procesar envío de stock
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bebida_id = $_POST['bebida'];
    $sucursal_id = $_POST['sucursal'];
    $cantidad = (int)$_POST['cantidad'];
    $fecha_envio = $_POST['fecha_envio'];

    try {
        $pdo->beginTransaction();

        // Obtener el stock actual
        $stmt = $pdo->prepare('
            SELECT 
                COALESCE(SUM(pb.cantidad), 0) - COALESCE((
                    SELECT SUM(sb.cantidad)
                    FROM sucursal_bebidas sb
                    WHERE sb.id_bebida = pb.id_bebida
                ), 0) AS stock_actual
            FROM proveedores_bebidas pb
            WHERE pb.id_bebida = :bebida_id
            GROUP BY pb.id_bebida
        ');
        $stmt->execute([':bebida_id' => $bebida_id]);
        $stock_actual = $stmt->fetchColumn();

        if ($stock_actual === false || $stock_actual < $cantidad) {
            throw new Exception("Stock insuficiente. Solo hay {$stock_actual} unidades disponibles.");
        }

        // Registrar el envío en sucursales_bebidas
        $stmt = $pdo->prepare('
            INSERT INTO sucursal_bebidas (id_bebida, id_sucursal, cantidad, fecha_envio) 
            VALUES (:bebida_id, :sucursal_id, :cantidad, :fecha_envio)
        ');
        $stmt->execute([
            ':bebida_id' => $bebida_id,
            ':sucursal_id' => $sucursal_id,
            ':cantidad' => $cantidad,
            ':fecha_envio' => $fecha_envio
        ]);

        // Reducir el stock en la tabla proveedores_bebidas
        $stmt = $pdo->prepare('
            UPDATE proveedores_bebidas
            SET cantidad = cantidad - :cantidad
            WHERE id_bebida = :bebida_id AND cantidad >= :cantidad
            LIMIT 1
        ');
        $stmt->execute([
            ':cantidad' => $cantidad,
            ':bebida_id' => $bebida_id
        ]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("No se pudo actualizar el stock. Verifica que la cantidad sea válida.");
        }

        $pdo->commit();
        $success = "Envío registrado correctamente y stock actualizado.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error al procesar el envío: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Gestión de Stock</title>
    <style>
 <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 1rem;
        }

        table th {
            background-color: #1ea02f;
            color: white;
        }

        table tr.low-stock {
            background-color:rgb(241, 241, 241); /* Fondo rojo claro */
        }

        table tr.low-stock td {
            color: #b30000; /* Texto rojo oscuro */
            font-weight: bold;
        }

        .message.success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .message.error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .low-stock { background-color:rgb(161, 45, 55); color: #721c24; }
    </style>
</head>
<body>
    <div class="stock-management">
        <h2>Stock Disponible</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Presentación</th>
                    <th>Cantidad</th>
                    <th>Stock Mínimo</th>
                    <th>Última Actualización</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para obtener el stock actual y el stock mínimo
                $stmt = $pdo->query('
                    SELECT 
                        b.nombre, 
                        p.descripcion AS presentacion,
                        COALESCE(SUM(pb.cantidad), 0) - COALESCE((
                            SELECT SUM(sb.cantidad)
                            FROM sucursal_bebidas sb
                            WHERE sb.id_bebida = b.id_bebida
                        ), 0) AS cantidad,
                        b.stock_minimo,
                        MAX(pb.fecha_ingreso) AS fecha_ingreso
                    FROM bebidas b
                    LEFT JOIN proveedores_bebidas pb ON b.id_bebida = pb.id_bebida
                    INNER JOIN presentaciones p ON b.id_presentacion = p.id_presentacion
                    GROUP BY b.id_bebida, b.stock_minimo, b.nombre, p.descripcion
                ');

                while ($row = $stmt->fetch()) {
                    $class = $row['cantidad'] < $row['stock_minimo'] ? 'low-stock' : '';
                    echo "<tr class='$class'>
                        <td>{$row['nombre']}</td>
                        <td>{$row['presentacion']}</td>
                        <td>{$row['cantidad']}</td>
                        <td>{$row['stock_minimo']}</td>
                        <td>{$row['fecha_ingreso']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Registrar Envío</h2>
        <?php if ($success) echo "<p class='message success'>$success</p>"; ?>
        <?php if ($error) echo "<p class='message error'>$error</p>"; ?>

        <form method="POST" action="">
            <!-- Selección de bebida -->
            <label for="bebida">Bebida:</label>
            <select name="bebida" id="bebida" required>
                <option value="" disabled selected>Seleccionar Bebida</option>
                <?php
                $bebidas = $pdo->query('
                    SELECT b.id_bebida, b.nombre, p.descripcion 
                    FROM bebidas b 
                    INNER JOIN presentaciones p ON b.id_presentacion = p.id_presentacion
                ');
                while ($bebida = $bebidas->fetch()) {
                    echo "<option value='{$bebida['id_bebida']}'>{$bebida['nombre']} - {$bebida['descripcion']}</option>";
                }
                ?>
            </select>

            <!-- Selección de sucursal -->
            <label for="sucursal">Sucursal:</label>
            <select name="sucursal" id="sucursal" required>
                <option value="" disabled selected>Seleccionar Sucursal</option>
                <?php
                $sucursales = $pdo->query('SELECT id_sucursal, direccion FROM sucursales');
                while ($sucursal = $sucursales->fetch()) {
                    echo "<option value='{$sucursal['id_sucursal']}'>{$sucursal['direccion']}</option>";
                }
                ?>
            </select>

            <!-- Fecha de envío -->
            <label for="fecha_envio">Fecha de Envío:</label>
            <input type="date" name="fecha_envio" id="fecha_envio" required>

            <!-- Cantidad -->
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" min="1" required>

            <!-- Botón de envío -->
            <button type="submit">Registrar Envío</button>
        </form>
    </div>
</body>
</html>

