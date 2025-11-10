<?php
require 'config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$action = $_GET['action'] ?? '';
$user_id = $_GET['user_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Usuario</title>
  <style>
  * {
    box-sizing: border-box;
  }

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
  <h1>Panel del Usuario</h1>
  <nav>
    <a href="admin.php">Inicio</a>
    <a href="admin.php?action=list_users">Usuarios</a>
    <a href="index.php" style="color: #f44336;">Cerrar sesión</a>
  </nav>
</header>

<div class="container">
<?php
if ($action === 'new_product') {
?>
  <div class="card">
    <h2>Crear Producto (Usuario ID <?=$user_id?>)</h2>
    <form method="post" action="user.php?action=create_product&user_id=<?=$user_id?>">
      <label>Nombre:</label><br>
      <input type="text" name="nombre" required><br><br>
      <label>Serial:</label><br>
      <input type="text" name="serial" required><br><br>
      <label>ID AI:</label><br>
      <input type="text" name="id_ai"><br><br>
      <button type="submit">Guardar</button>
    </form>
  </div>
<?php
} elseif ($action === 'create_product' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $serial = $_POST['serial'];
    $id_ai = $_POST['id_ai'];

    $stmt = $pdo->prepare("INSERT INTO products (nombre, serial, id_ai, created_by_user) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $serial, $id_ai, $user_id]);

    echo "<div class='card'><p>Producto creado correctamente.</p>";
    echo "<a href='user.php?action=list_products&user_id=$user_id'><button>Ver productos</button></a></div>";

} elseif ($action === 'list_products') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE created_by_user = ?");
    $stmt->execute([$user_id]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="card">
      <h2>Productos del Usuario #<?=$user_id?></h2>
      <a href="user.php?action=new_product&user_id=<?=$user_id?>"><button>Agregar Producto</button></a>
      <table>
        <tr><th>ID</th><th>Nombre</th><th>Serial</th><th>ID AI</th><th>Creado</th></tr>
        <?php foreach($products as $p): ?>
          <tr>
            <td><?=$p['id']?></td>
            <td><?=htmlspecialchars($p['nombre'])?></td>
            <td><?=htmlspecialchars($p['serial'])?></td>
            <td><?=htmlspecialchars($p['id_ai'])?></td>
            <td><?=$p['created_at']?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
    <?php
} else {
?>
  <div class="card">
    <h2>Gestión de Productos</h2>
    <p>Selecciona un usuario desde el panel de administrador para crear o listar sus productos.</p>
  </div>
<?php
}
?>
</div>

</body>
</html>
