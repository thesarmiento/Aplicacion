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
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Administrador - Login</title>
<style>
body {
  background-color: #121212;
  color: #e0e0e0;
  font-family: "Segoe UI", Arial, sans-serif;
  margin: 0;
  padding: 0;
}

.content {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 20px;
}

.card {
  background-color: #1f1f1f;
  border: 1px solid #333;
  border-radius: 12px;
  padding: 30px;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 0 10px rgba(0,0,0,0.4);
}

.card h2 {
  text-align: center;
  color: #ffffff;
  margin-bottom: 20px;
}

form label {
  display: block;
  margin: 10px 0 5px;
}

input[type="text"],
input[type="password"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #444;
  border-radius: 8px;
  background-color: #2b2b2b;
  color: #fff;
  font-size: 1rem;
  box-sizing: border-box;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #00bcd4;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  margin-top: 15px;
  font-size: 1rem;
  font-weight: bold;
  transition: background-color 0.3s;
}

button:hover {
  background-color: #43a047;
}

.error {
  color: #ff4d4d;
  text-align: center;
  margin-bottom: 10px;
}
</style>
</head>
<body>

<main class="content">
  <div class="card">
    <h2>Panel de Administración</h2>
    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
      <label>Usuario:</label>
      <input type="text" name="nic" required>

      <label>Contraseña:</label>
      <input type="password" name="password" required>

      <button type="submit">Entrar</button>
    </form>
  </div>
</main>

</body>
</html>
