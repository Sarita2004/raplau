<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bebida_id = $_POST['bebida'];
    $cantidad = $_POST['cantidad'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $proveedor_id = $_POST['prov'];

    try {
        // Insertar nueva carga en la tabla de proveedores_bebidas
        $stmt = $pdo->prepare('
            INSERT INTO proveedores_bebidas (id_bebida, cantidad, fecha_ingreso, id_prov) 
            VALUES (:bebida_id, :cantidad, :fecha_ingreso, :proveedor_id)
        ');

        $stmt->execute([
            ':bebida_id' => $bebida_id,
            ':cantidad' => $cantidad,
            ':fecha_ingreso' => $fecha_ingreso,
            ':proveedor_id' => $proveedor_id
        ]);

        // Redirigir a entries.php para mostrar el stock actualizado
        header('Location: entries.php');
        exit;
    } catch (PDOException $e) {
        $error = "Error al agregar el stock: " . $e->getMessage();
    }
}
?>

