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
    <title>Gestión de Envíos</title>
</head>
<body>
    <div class="exits">
        <h2>Stock Disponible</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Presentación</th>
                    <th>Cantidad</th>
                    <th>Última Actualización</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Obtener el stock disponible de las bebidas con sus presentaciones
                $stmt = $pdo->query('
                    SELECT 
                        b.nombre, 
                        p.descripcion AS presentacion, 
                        SUM(pb.cantidad) AS cantidad, 
                        MAX(pb.fecha_ingreso) AS fecha_ingreso
                    FROM bebidas b
                    INNER JOIN proveedores_bebidas pb ON b.id_bebida = pb.id_bebida
                    INNER JOIN presentaciones p ON b.id_presentacion = p.id_presentacion
                    GROUP BY b.nombre, p.descripcion
                ');

                while ($row = $stmt->fetch()) {
                    $class = $row['cantidad'] < 100 ? 'low-stock' : '';
                    echo "<tr class='$class'>
                        <td>{$row['nombre']}</td>
                        <td>{$row['presentacion']}</td>
                        <td>{$row['cantidad']}</td>
                        <td>{$row['fecha_ingreso']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Registrar Envío</h2>
        <form method="POST" action="remove_stock.php">
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

            <!-- Fecha de envío -->
            <label for="fecha_envio">Fecha de Envío:</label>
            <input type="date" name="fecha_envio" id="fecha_envio" required>

            <!-- Cantidad -->
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" min="1" required>

            <!-- Botón de envío -->
            <button type="submit">Registrar Envío</button>
        </form>
        
        <!-- Mostrar mensaje de éxito o error -->
        <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
    </div>
</body>
</html>
