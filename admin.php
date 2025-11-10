<?php
require 'config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'create_user' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $cod = trim($_POST['cod']);
    $password = trim($_POST['password']); 
    $id_ai = trim($_POST['id_ai']);
    $adminId = $_SESSION['admin']['id'];

    $stmt = $pdo->prepare("INSERT INTO users (nombre, cod, password, id_ai, created_by_admin) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $cod, $password, $id_ai, $adminId]);

    echo "<p class='success'>Usuario creado exitosamente.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración</title>
  <style>
  
  * { box-sizing: border-box; }

  body {
    background-color: #121212;
    color: #e0e0e0;
    font-family: "Segoe UI", Arial, sans-serif;
    margin: 0;
    padding: 0;
  }

  header {
    background-color: #1e1e1e;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #00bcd4;
  }

  header h1 {
    color: #00bcd4;
    margin: 0;
    font-size: 22px;
    letter-spacing: 1px;
  }

  nav a {
    color: #ddd;
    text-decoration: none;
    margin-left: 20px;
    font-weight: 500;
    transition: color 0.3s;
  }

  nav a:hover {
    color: #00bcd4;
  }

  .container {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
  }

  .card {
    background-color: #1f1f1f;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0,0,0,0.6);
    margin-bottom: 25px;
  }

  h2 {
    color: #00bcd4;
    border-bottom: 2px solid #333;
    padding-bottom: 8px;
    margin-bottom: 20px;
    font-size: 20px;
  }

  input, button {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #333;
    background-color: #2a2a2a;
    color: #fff;
    margin-bottom: 10px;
  }

  button {
    background-color: #00bcd4;
    color: #000;
    font-weight: bold;
    cursor: pointer;
    border: none;
    transition: 0.3s;
  }

  button:hover {
    background-color: #0097a7;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
  }

  th, td {
    padding: 10px;
    border-bottom: 1px solid #333;
    text-align: left;
  }

  th {
    background-color: #242424;
    color: #00bcd4;
  }

  tr:hover {
    background-color: #1a1a1a;
  }

  a {
    color: #00bcd4;
    text-decoration: none;
  }

  a:hover {
    text-decoration: underline;
  }
  </style>
</head>
<body>

<header>
  <h1>Panel de Administración</h1>
  <nav>
    <a href="admin.php">Inicio</a>
    <a href="admin.php?action=list_users">Usuarios</a>
    <a href="index.php" style="color: #f44336;">Cerrar sesión</a>
  </nav>
</header>

<div class="container">
<?php
if ($action === 'list_users') {
    $adminId = $_SESSION['admin']['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE created_by_admin = ?");
    $stmt->execute([$adminId]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
  <div class="card">
    <h2>Usuarios creados por <?= htmlspecialchars($_SESSION['admin']['nic_name']) ?></h2>
    <a href="admin.php?action=new_user"><button>Crear nuevo usuario</button></a>
    <table>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Código</th>
        <th>ID AI</th>
        <th>Acciones</th>
      </tr>
      <?php foreach ($users as $u): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['nombre']) ?></td>
        <td><?= htmlspecialchars($u['cod']) ?></td>
        <td><?= htmlspecialchars($u['id_ai']) ?></td>
        <td>
          <a href="user.php?action=list_products&user_id=<?= $u['id'] ?>">Ver Productos</a> |
          <a href="user.php?action=new_product&user_id=<?= $u['id'] ?>">Agregar Producto</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
<?php
} elseif ($action === 'new_user') {
?>
  <div class="card">
    <h2>Crear Usuario</h2>
    <form method="post" action="admin.php?action=create_user">
      <label>Nombre:</label>
      <input type="text" name="nombre" required>

      <label>Código:</label>
      <input type="text" name="cod" required>

      <label>Contraseña:</label>
      <input type="text" name="password" required>

      <label>ID AI:</label>
      <input type="text" name="id_ai">

      <button type="submit">Guardar</button>
    </form>
  </div>
<?php
} else {
?>
  <div class="card">
    <h2>Bienvenido, <?= htmlspecialchars($_SESSION['admin']['nic_name']) ?></h2>
    <p>Desde aquí puedes administrar los usuarios y sus productos.</p>
    <a href="admin.php?action=list_users"><button>Ver Usuarios</button></a>
  </div>
<?php
}
?>
</div>

</body>
</html>
