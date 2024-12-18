<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    try {
        $stmt = $pdo->prepare("INSERT INTO sucursales ( direccion, telefono) VALUES (:direccion, :telefono)");
        $stmt->execute([
    
            ':direccion' => $direccion,
            ':telefono' => $telefono
        ]);
        $success = "Sucursal agregada exitosamente.";
    } catch (PDOException $e) {
        $error = "Error al agregar la sucursal: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Agregar Sucursal</title>
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

        .form-container input[type="text"] {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

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
        <h1>Nueva Sucursal</h1>
        <form method="POST">
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="tel" name="telefono" placeholder="Teléfono" required>
            <button type="submit">Agregar Sucursal</button>
        </form>
        <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
        <br></br>
        <a href="mau.php" class="back-button">Volver al Inicio</a>
        <a href="sucursal.php" class="back-button">Editar sucursales</a>
    </div>
</body>
</html>
