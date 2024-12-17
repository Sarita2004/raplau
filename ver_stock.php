<?php
require 'navbar.php';
require 'db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Stock de Bebidas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .low-stock {
            background-color:rgb(226, 104, 115);
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>Stock Disponible de Bebidas</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre de la Bebida</th>
                <th>Presentación</th>
                <th>Cantidad Disponible</th>
                <th>Última Fecha de Ingreso</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta para obtener el stock disponible de bebidas agrupado por bebida y presentación
            $stmt = $pdo->query('
                SELECT 
                    b.nombre AS bebida, 
                    p.descripcion AS presentacion, 
                    SUM(pb.cantidad) AS cantidad, 
                    MAX(pb.fecha_ingreso) AS fecha_ingreso
                FROM bebidas b
                INNER JOIN proveedores_bebidas pb ON b.id_bebida = pb.id_bebida
                INNER JOIN presentaciones p ON b.id_presentacion = p.id_presentacion
                GROUP BY b.nombre, p.descripcion
                ORDER BY b.nombre
            ');

            // Mostrar los resultados en la tabla
            while ($row = $stmt->fetch()) {
                $class = $row['cantidad'] < 100 ? 'low-stock' : ''; // Clase para stock bajo
                echo "<tr class='$class'>
                    <td>{$row['bebida']}</td>
                    <td>{$row['presentacion']}</td>
                    <td>{$row['cantidad']}</td>
                    <td>{$row['fecha_ingreso']}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
