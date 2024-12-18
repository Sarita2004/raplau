<?php
require 'db.php';
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Gestión de Stock</title>
    <style>
                table tr.low-stock td {
            color: #b30000; /* Texto rojo oscuro */
            font-weight: bold;
        }
        .low-stock { /* Menor al stock mínimo */
            background-color: rgb(230, 214, 214);
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="entries">
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
                // Obtener el stock disponible y el stock mínimo de cada bebida
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
                    INNER JOIN proveedores_bebidas pb ON b.id_bebida = pb.id_bebida
                    INNER JOIN presentaciones p ON b.id_presentacion = p.id_presentacion
                    GROUP BY b.id_bebida, b.nombre, p.descripcion, b.stock_minimo
                ');

                while ($row = $stmt->fetch()) {
                    // Asignar clase según el stock actual comparado con el stock mínimo
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

        <h2>Registrar Nueva Carga</h2>
        <form method="POST" action="add_stock.php">
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

            <!-- Selección de proveedor -->
            <label for="prov">Proveedor:</label>
            <select name="prov" id="prov" required>
                <option value="" disabled selected>Seleccionar Proveedor</option>
                <?php
                $proveedores = $pdo->query('SELECT id_prov, nombre FROM proveedores');
                while ($proveedor = $proveedores->fetch()) {
                    echo "<option value='{$proveedor['id_prov']}'>{$proveedor['nombre']}</option>";
                }
                ?>
            </select>

            <!-- Fecha de ingreso -->
            <label for="fecha_ingreso">Fecha de Ingreso:</label>
            <input type="date" name="fecha_ingreso" id="fecha_ingreso" required>

            <!-- Cantidad -->
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" min="1" required>

            <!-- Botón de envío -->
            <button type="submit">Registrar Carga</button>
        </form>
        
        <!-- Mostrar mensaje de éxito o error -->
        <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
    </div>
</body>
</html>
