<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="dashboard">
        <h1>Dashboard</h1>
        <a href="entries.php">Manage Entries</a>
        <a href="exits.php">Manage Exits</a>
    </div>
</body>
</html>