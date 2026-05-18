<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_admin();

$keys = ['site_name', 'site_tagline', 'site_description', 'contact_email', 'contact_phone', 'contact_address', 'hero_title', 'hero_text'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ($keys as $key) {
        execute_query('INSERT INTO configuracion_sitio (clave, valor) VALUES (?, ?) ON DUPLICATE KEY UPDATE valor = VALUES(valor)', [$key, trim($_POST[$key] ?? '')]);
    }
    flash('success', 'Configuracion guardada.');
    redirect_to('admin/configuracion.php');
}
$values = [];
foreach (fetch_all('SELECT clave, valor FROM configuracion_sitio') as $row) $values[$row['clave']] = $row['valor'];
$layout = 'admin';
$pageTitle = 'Configuracion | Admin';
include __DIR__ . '/../includes/header.php';
?>
<div class="admin-top"><div><p class="section-kicker">Sitio</p><h1>Configuracion</h1></div></div>
<?php if ($message = flash('success')): ?><div class="alert alert--success"><?= e($message) ?></div><?php endif; ?>
<form class="admin-card admin-form" method="post">
  <?= csrf_field() ?>
  <?php foreach ($keys as $key): ?>
    <label class="field"><?= e($key) ?><textarea class="form-control" name="<?= e($key) ?>" rows="<?= in_array($key, ['site_description', 'hero_text', 'site_tagline'], true) ? 3 : 1 ?>"><?= e($values[$key] ?? '') ?></textarea></label>
  <?php endforeach; ?>
  <button class="btn btn--dark" type="submit">Guardar configuracion</button>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>

