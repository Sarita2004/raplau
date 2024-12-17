<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Tablero Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 100%;
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            max-width: 1000px;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 250px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .card p {
            font-size: 1rem;
            color: #555;
        }

        .card a {
            color: inherit;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Tablero Principal</h1>
        <div class="dashboard">
            <!-- Tarjeta para Agregar Bebida -->
            <a href="bebida.php" class="card">
                <h3>Agregar Bebida</h3>
                <p>Registra nuevas bebidas.</p>
            </a>

            <!-- Tarjeta para Ingresos -->
            <a href="entries.php" class="card">
                <h3>Ingresos</h3>
                <p>Gestiona el ingreso de stock.</p>
            </a>

            <!-- Tarjeta para Envíos -->
            <a href="exits.php" class="card">
                <h3>Envíos</h3>
                <p>Gestiona los envíos realizados a las sucursales.</p>
            </a>

            <!-- Tarjeta para Ver Stock -->
            <a href="ver_stock.php" class="card">
                <h3>Ver Stock</h3>
                <p>Consulta el stock disponible de bebidas.</p>
            </a>

            <!-- Tarjeta para Agregar Sucursal -->
            <a href="sucursales.php" class="card">
                <h3>Agregar Sucursal</h3>
                <p>Registra nuevas sucursales.</p>
            </a>

            <!-- Tarjeta para Agregar Proveedor -->
            <a href="proveedor.php" class="card">
                <h3>Agregar Proveedor</h3>
                <p>Registra nuevos proveedores.</p>
            </a>
        </div>
    </div>
</body>
</html>
