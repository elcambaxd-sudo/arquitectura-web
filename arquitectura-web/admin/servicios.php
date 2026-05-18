<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = (int) ($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'delete') {
        execute_query('DELETE FROM servicios WHERE id = ?', [$id]);
        flash('success', 'Servicio eliminado.');
    } else {
        $titulo = trim($_POST['titulo'] ?? '');
        $slug = trim($_POST['slug'] ?? '') ?: slugify($titulo);
        $params = [$titulo, $slug, trim($_POST['descripcion'] ?? ''), trim($_POST['beneficio_cliente'] ?? ''), trim($_POST['icono'] ?? 'square'), (int) ($_POST['orden'] ?? 0), isset($_POST['estado']) ? 1 : 0];
        if ($id) execute_query('UPDATE servicios SET titulo=?, slug=?, descripcion=?, beneficio_cliente=?, icono=?, orden=?, estado=? WHERE id=?', [...$params, $id]);
        else execute_query('INSERT INTO servicios (titulo, slug, descripcion, beneficio_cliente, icono, orden, estado) VALUES (?, ?, ?, ?, ?, ?, ?)', $params);
        flash('success', 'Servicio guardado.');
    }
    redirect_to('admin/servicios.php');
}

$edit = isset($_GET['id']) ? fetch_one('SELECT * FROM servicios WHERE id = ?', [(int) $_GET['id']]) : null;
$items = fetch_all('SELECT * FROM servicios ORDER BY orden, id');
$layout = 'admin';
$pageTitle = 'Servicios | Admin';
include __DIR__ . '/../includes/header.php';
?>
<div class="admin-top"><div><p class="section-kicker">Contenido</p><h1>Servicios</h1></div></div>
<?php if ($message = flash('success')): ?><div class="alert alert--success"><?= e($message) ?></div><?php endif; ?>
<form class="admin-card admin-form" method="post">
  <?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) ($edit['id'] ?? 0)) ?>">
  <div class="three"><label class="field">Titulo<input class="form-control" name="titulo" value="<?= e($edit['titulo'] ?? '') ?>" required></label><label class="field">Slug<input class="form-control" name="slug" value="<?= e($edit['slug'] ?? '') ?>"></label><label class="field">Icono<input class="form-control" name="icono" value="<?= e($edit['icono'] ?? '') ?>"></label></div>
  <label class="field">Descripcion<textarea class="form-control" name="descripcion" rows="3"><?= e($edit['descripcion'] ?? '') ?></textarea></label>
  <label class="field">Beneficio para cliente<textarea class="form-control" name="beneficio_cliente" rows="3"><?= e($edit['beneficio_cliente'] ?? '') ?></textarea></label>
  <label class="field">Orden<input class="form-control" type="number" name="orden" value="<?= e((string) ($edit['orden'] ?? 0)) ?>"></label>
  <label><input type="checkbox" name="estado" <?= ($edit['estado'] ?? 1) ? 'checked' : '' ?>> Activo</label>
  <button class="btn btn--dark" type="submit">Guardar servicio</button>
</form>
<div class="admin-table-wrap" style="margin-top:1.5rem"><table class="admin-table"><thead><tr><th>Titulo</th><th>Orden</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($items as $item): ?><tr><td><?= e($item['titulo']) ?></td><td><?= e((string) $item['orden']) ?></td><td><?= $item['estado'] ? 'Activo' : 'Oculto' ?></td><td><a class="btn btn--small" href="<?= e(url('admin/servicios.php?id=' . $item['id'])) ?>">Editar</a><form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) $item['id']) ?>"><button class="btn btn--small" name="action" value="delete">Eliminar</button></form></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

