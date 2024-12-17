<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $categoria_id = $_POST['categoria'];
    $presentacion_id = $_POST['presentacion'];

    // Inserción en la base de datos
    $stmt = $pdo->prepare('
        INSERT INTO bebidas (nombre, id_categoria, id_presentacion)
        VALUES (:nombre, :categoria, :presentacion)
    ');

    $stmt->execute([
        ':nombre' => $nombre,
        ':categoria' => $categoria_id,
        ':presentacion' => $presentacion_id
    ]);

    // Redirección o mensaje de éxito
    header('Location: bebida.php'); // Cambia según tus necesidades.
    exit();
}
?>
