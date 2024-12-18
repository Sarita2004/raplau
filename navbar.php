<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Navbar</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Navbar styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            color: white;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .navbar a:hover {
            background-color: #555;
        }

        .navbar .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar .navbar-right {
            display: flex;
            gap: 10px;
        }

        /* Responsive styles */
        @media (max-width: 600px) {
            .navbar a {
                padding: 8px 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-left">
            <a href="mau.php">Inicio</a>
        </div>
        <div class="navbar-right">
            <a href="ver_stock.php">Ver Stock</a>
            <a href="stock_management.php">Envíos</a>
            <a href="entries.php">Ingresos</a>
        </div>
    </nav>
</body>
</html>
