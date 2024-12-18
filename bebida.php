<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $presentacion = $_POST['presentacion'];
    $stock_minimo = $_POST['stock_minimo'];

    try {
        $stmt = $pdo->prepare("INSERT INTO bebidas (nombre, id_categoria, id_presentacion, stock_minimo) VALUES (:nombre, :categoria, :presentacion, :stock_minimo)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':categoria' => $categoria,
            ':presentacion' => $presentacion,
            ':stock_minimo' => $stock_minimo
        ]);
        $success = "Bebida agregada exitosamente.";
    } catch (PDOException $e) {
        $error = "Error al agregar la bebida: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Agregar Bebida</title>
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

        .form-container {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container select {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 10px;
            font-size: 0.9rem;
            text-align: center;
        }

        .message.success {
            color: #4CAF50;
        }

        .message.error {
            color: #f44336;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Agregar Bebida</h1>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre de la Bebida" required>
            
            <!-- Selección de Categoría -->
            <select name="categoria" required>
                <option value="">Selecciona una categoría</option>
                <?php
                $categorias = $pdo->query('SELECT * FROM categorias');
                while ($cat = $categorias->fetch()) {
                    echo "<option value='{$cat['id_categoria']}'>{$cat['nombre']}</option>";
                }
                ?>
            </select>

            <!-- Selección de Presentación -->
            <label for="presentacion">Presentación:</label>
            <select name="presentacion" required>
                <option value="" disabled selected>Selecciona una presentación</option>
                <?php
                $presentaciones = $pdo->query('SELECT * FROM presentaciones');
                while ($presentacion = $presentaciones->fetch()) {
                    echo "<option value='{$presentacion['id_presentacion']}'>{$presentacion['descripcion']}</option>";
                }
                ?>
            </select>

            <!-- Stock Mínimo -->
            <label for="stock_minimo">Stock Mínimo:</label>
            <input type="number" name="stock_minimo" placeholder="Stock mínimo" min="0" required>

            <button type="submit">Agregar Bebida</button>
        </form>
        
        <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
        
        <a href="mau.php" class="back-button">Volver al Inicio</a>
        <a href="bebidas.php" class="back-button">Editar bebidas</a>
    </div>
</body>
</html>
