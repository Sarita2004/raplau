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

        table tr.low-stock td {
            color: #b30000; /* Texto rojo oscuro */
            font-weight: bold;
        }
        .low-stock {
            background-color: rgb(228, 224, 224);
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
                <th>Stock Mínimo</th>
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
                GROUP BY b.nombre, p.descripcion, b.stock_minimo
                ORDER BY b.nombre
            ');

            // Mostrar los resultados en la tabla
            while ($row = $stmt->fetch()) {
                $class = $row['cantidad'] < $row['stock_minimo'] ? 'low-stock' : ''; // Clase para stock bajo
                echo "<tr class='$class'>
                    <td>{$row['bebida']}</td>
                    <td>{$row['presentacion']}</td>
                    <td>{$row['cantidad']}</td>
                    <td>{$row['stock_minimo']}</td>
                    <td>{$row['fecha_ingreso']}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
