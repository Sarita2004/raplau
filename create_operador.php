<?php
require 'db.php';

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_operator'])) {
    $usuario = $_POST['usuario'];
    $password = hashPassword($_POST['password']);

    $stmt = $pdo->prepare('INSERT INTO operadores (usuario, password) VALUES (?, ?)');
    $stmt->execute([$usuario, $password]);

    $success = "Operador creado correctamente.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Operador</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, rgb(229, 227, 231), rgb(149, 233, 167));
            color: #fff;
        }

        .operator-container {
            background: rgb(24, 156, 57);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 700;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 1rem;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
        }

        button {
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            background: rgb(7, 85, 14);
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: rgb(17, 203, 95);
        }

        .success {
            margin-top: 1rem;
            color: #4CAF50;
            font-size: 0.9rem;
        }
        .create-user {
            margin-top: 1rem;
        }

        .create-user a {
            color: #ffcc00;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .create-user a:hover {
            text-decoration: underline;
        }


        @media (max-width: 480px) {
            .operator-container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            input, button {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="operator-container">
        <h1>Crear Operador</h1>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="create_operator">Crear</button>
        </form>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <div class="create-user">
            <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesion</a></p>
        </div>
    </div>

</body>
</html>
