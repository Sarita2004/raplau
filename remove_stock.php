<?php
ob_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bebida_id = $_POST['bebida'];
    $cantidad = $_POST['cantidad'];
    $fecha_envio = $_POST['fecha_envio'];

    try {
        // Obtener el stock actual
        $stmt = $pdo->prepare('
            SELECT SUM(pb.cantidad) AS stock_actual
            FROM proveedores_bebidas pb
            WHERE pb.id_bebida = :bebida_id
        ');
        $stmt->execute([':bebida_id' => $bebida_id]);
        $stock_actual = $stmt->fetchColumn();

        if ($stock_actual === false || $stock_actual < $cantidad) {
            throw new Exception("Stock insuficiente. Solo hay {$stock_actual} unidades disponibles.");
        }

        // Registrar el envío restando la cantidad del stock
        $stmt = $pdo->prepare('
            INSERT INTO proveedores_bebidas (id_bebida, cantidad, fecha_ingreso, id_prov) 
            VALUES (:bebida_id, :cantidad, :fecha_envio, NULL)
        ');
        $stmt->execute([
            ':bebida_id' => $bebida_id,
            ':cantidad' => -$cantidad, // Cantidad negativa para restar del stock
            ':fecha_envio' => $fecha_envio
        ]);
        echo "Redirigiendo...";

        // Redirigir a entries.php para mostrar el stock actualizado
        header('Location: stock_management.php');
        exit; // Importante para detener la ejecución después de la redirección
    } catch (Exception $e) {
        $error = "Error al procesar el envío: " . $e->getMessage();
    }
}
?>

