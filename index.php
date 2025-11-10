<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nic = trim($_POST['nic']);
    $pass = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE nic_name = ?");
    $stmt->execute([$nic]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && $pass === $admin['password']) {
        $_SESSION['admin'] = $admin;
        header('Location: admin.php');
        exit;
    } else {
        echo "<p style='color:red;'>Credenciales incorrectas</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Administrador</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <div class="card">
    <h2>Login Administrador</h2>
    <form method="post">
      <label>Nombre:</label>
      <input type="text" name="nic" required>

      <label>Contraseña:</label>
      <input type="password" name="password" required>

      <button type="submit">Entrar</button>
    </form>
  </div>
</div>

</body>
</html>
