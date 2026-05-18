<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$layout = 'admin';
$pageTitle = 'Dashboard | Admin';
$stats = [
    'Proyectos' => fetch_one('SELECT COUNT(*) total FROM proyectos')['total'] ?? 0,
    'Servicios' => fetch_one('SELECT COUNT(*) total FROM servicios')['total'] ?? 0,
    'Equipo' => fetch_one('SELECT COUNT(*) total FROM equipo')['total'] ?? 0,
    'Mensajes' => fetch_one('SELECT COUNT(*) total FROM mensajes_contacto')['total'] ?? 0,
];
$messages = fetch_all('SELECT * FROM mensajes_contacto ORDER BY fecha_envio DESC LIMIT 5');
include __DIR__ . '/../includes/header.php';
?>
<div class="admin-top">
  <div>
    <p class="section-kicker">Bienvenido</p>
    <h1>Dashboard</h1>
  </div>
  <a class="btn" href="<?= e(url()) ?>" target="_blank">Ver web</a>
</div>
<div class="grid grid--4">
  <?php foreach ($stats as $label => $value): ?>
    <div class="stat-card">
      <p class="section-kicker"><?= e($label) ?></p>
      <h2><?= e((string) $value) ?></h2>
    </div>
  <?php endforeach; ?>
</div>
<div class="admin-table-wrap" style="margin-top:1.5rem">
  <h2>Mensajes recientes</h2>
  <table class="admin-table">
    <thead><tr><th>Nombre</th><th>Email</th><th>Tipo</th><th>Fecha</th></tr></thead>
    <tbody>
      <?php foreach ($messages as $message): ?>
        <tr><td><?= e($message['nombre']) ?></td><td><?= e($message['email']) ?></td><td><?= e($message['tipo_proyecto']) ?></td><td><?= e($message['fecha_envio']) ?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

