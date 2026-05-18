<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? 'save';
    if ($action === 'delete') {
        execute_query('DELETE FROM categorias_proyecto WHERE id = ?', [$id]);
        flash('success', 'Categoria eliminada.');
    } else {
        $nombre = trim($_POST['nombre'] ?? '');
        $slug = trim($_POST['slug'] ?? '') ?: slugify($nombre);
        $descripcion = trim($_POST['descripcion'] ?? '');
        $orden = (int) ($_POST['orden'] ?? 0);
        $estado = isset($_POST['estado']) ? 1 : 0;
        if ($id) {
            execute_query('UPDATE categorias_proyecto SET nombre=?, slug=?, descripcion=?, orden=?, estado=? WHERE id=?', [$nombre, $slug, $descripcion, $orden, $estado, $id]);
        } else {
            execute_query('INSERT INTO categorias_proyecto (nombre, slug, descripcion, orden, estado) VALUES (?, ?, ?, ?, ?)', [$nombre, $slug, $descripcion, $orden, $estado]);
        }
        flash('success', 'Categoria guardada.');
    }
    redirect_to('admin/categorias.php');
}

$edit = isset($_GET['id']) ? fetch_one('SELECT * FROM categorias_proyecto WHERE id = ?', [(int) $_GET['id']]) : null;
$items = fetch_all('SELECT * FROM categorias_proyecto ORDER BY orden, id');
$layout = 'admin';
$pageTitle = 'Categorias | Admin';
include __DIR__ . '/../includes/header.php';
?>
<div class="admin-top"><div><p class="section-kicker">Proyectos</p><h1>Categorias</h1></div></div>
<?php if ($message = flash('success')): ?><div class="alert alert--success"><?= e($message) ?></div><?php endif; ?>
<form class="admin-card admin-form" method="post">
  <?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) ($edit['id'] ?? 0)) ?>">
  <div class="three">
    <label class="field">Nombre<input class="form-control" name="nombre" value="<?= e($edit['nombre'] ?? '') ?>" required></label>
    <label class="field">Slug<input class="form-control" name="slug" value="<?= e($edit['slug'] ?? '') ?>"></label>
    <label class="field">Orden<input class="form-control" type="number" name="orden" value="<?= e((string) ($edit['orden'] ?? 0)) ?>"></label>
  </div>
  <label class="field">Descripcion<textarea class="form-control" name="descripcion" rows="3"><?= e($edit['descripcion'] ?? '') ?></textarea></label>
  <label><input type="checkbox" name="estado" <?= ($edit['estado'] ?? 1) ? 'checked' : '' ?>> Activa</label>
  <button class="btn btn--dark" type="submit">Guardar categoria</button>
</form>
<div class="admin-table-wrap" style="margin-top:1.5rem">
  <table class="admin-table"><thead><tr><th>Nombre</th><th>Slug</th><th>Orden</th><th>Acciones</th></tr></thead><tbody>
  <?php foreach ($items as $item): ?><tr>
    <td><?= e($item['nombre']) ?></td><td><?= e($item['slug']) ?></td><td><?= e((string) $item['orden']) ?></td>
    <td><a class="btn btn--small" href="<?= e(url('admin/categorias.php?id=' . $item['id'])) ?>">Editar</a><form method="post" style="display:inline" onsubmit="return confirm('¿Eliminar categoria?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) $item['id']) ?>"><button class="btn btn--small" name="action" value="delete">Eliminar</button></form></td>
  </tr><?php endforeach; ?></tbody></table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

