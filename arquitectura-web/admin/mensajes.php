<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    execute_query('UPDATE mensajes_contacto SET estado = ? WHERE id = ?', [$_POST['estado'] ?? 'leido', (int) $_POST['id']]);
    flash('success', 'Mensaje actualizado.');
    redirect_to('admin/mensajes.php');
}

$messages = fetch_all('SELECT * FROM mensajes_contacto ORDER BY fecha_envio DESC');
$layout = 'admin';
$pageTitle = 'Mensajes | Admin';
include __DIR__ . '/../includes/header.php';
?>
<div class="admin-top"><div><p class="section-kicker">Contacto</p><h1>Mensajes</h1></div></div>
<?php if ($message = flash('success')): ?><div class="alert alert--success"><?= e($message) ?></div><?php endif; ?>
<div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Datos</th><th>Proyecto</th><th>Mensaje</th><th>Estado</th></tr></thead><tbody>
<?php foreach ($messages as $item): ?><tr><td><strong><?= e($item['nombre']) ?></strong><br><?= e($item['email']) ?><br><?= e($item['telefono']) ?></td><td><?= e($item['tipo_proyecto']) ?><br><?= e($item['presupuesto_estimado']) ?></td><td><?= e($item['mensaje']) ?><br><span class="badge"><?= e($item['fecha_envio']) ?></span></td><td><form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) $item['id']) ?>"><select class="form-control" name="estado"><option <?= $item['estado']==='nuevo'?'selected':'' ?>>nuevo</option><option <?= $item['estado']==='leido'?'selected':'' ?>>leido</option><option <?= $item['estado']==='respondido'?'selected':'' ?>>respondido</option></select><button class="btn btn--small" style="margin-top:.5rem">Guardar</button></form></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

