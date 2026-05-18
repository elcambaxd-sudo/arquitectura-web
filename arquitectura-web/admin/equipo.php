<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = (int) ($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'delete') {
        execute_query('DELETE FROM equipo WHERE id = ?', [$id]);
        flash('success', 'Miembro eliminado.');
    } else {
        $current = $id ? fetch_one('SELECT * FROM equipo WHERE id=?', [$id]) : null;
        $foto = $current['foto'] ?? null;
        if (!empty($_FILES['foto']['name'])) $foto = safe_upload($_FILES['foto'], 'team');
        $params = [trim($_POST['nombre'] ?? ''), trim($_POST['cargo'] ?? ''), trim($_POST['especialidad'] ?? ''), trim($_POST['biografia'] ?? ''), $foto, (int) ($_POST['orden'] ?? 0), isset($_POST['estado']) ? 1 : 0];
        if ($id) execute_query('UPDATE equipo SET nombre=?, cargo=?, especialidad=?, biografia=?, foto=?, orden=?, estado=? WHERE id=?', [...$params, $id]);
        else execute_query('INSERT INTO equipo (nombre, cargo, especialidad, biografia, foto, orden, estado) VALUES (?, ?, ?, ?, ?, ?, ?)', $params);
        flash('success', 'Equipo guardado.');
    }
    redirect_to('admin/equipo.php');
}

$edit = isset($_GET['id']) ? fetch_one('SELECT * FROM equipo WHERE id = ?', [(int) $_GET['id']]) : null;
$items = fetch_all('SELECT * FROM equipo ORDER BY orden, id');
$layout = 'admin';
$pageTitle = 'Equipo | Admin';
include __DIR__ . '/../includes/header.php';
?>
<div class="admin-top"><div><p class="section-kicker">Contenido</p><h1>Equipo</h1></div></div>
<?php if ($message = flash('success')): ?><div class="alert alert--success"><?= e($message) ?></div><?php endif; ?>
<form class="admin-card admin-form" method="post" enctype="multipart/form-data">
  <?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) ($edit['id'] ?? 0)) ?>">
  <div class="three"><label class="field">Nombre<input class="form-control" name="nombre" value="<?= e($edit['nombre'] ?? '') ?>" required></label><label class="field">Cargo<input class="form-control" name="cargo" value="<?= e($edit['cargo'] ?? '') ?>"></label><label class="field">Especialidad<input class="form-control" name="especialidad" value="<?= e($edit['especialidad'] ?? '') ?>"></label></div>
  <label class="field">Biografia<textarea class="form-control" name="biografia" rows="4"><?= e($edit['biografia'] ?? '') ?></textarea></label>
  <div class="two"><label class="field">Foto<input class="form-control" type="file" name="foto" accept=".jpg,.jpeg,.png,.webp"></label><label class="field">Orden<input class="form-control" type="number" name="orden" value="<?= e((string) ($edit['orden'] ?? 0)) ?>"></label></div>
  <label><input type="checkbox" name="estado" <?= ($edit['estado'] ?? 1) ? 'checked' : '' ?>> Activo</label>
  <button class="btn btn--dark" type="submit">Guardar miembro</button>
</form>
<div class="admin-table-wrap" style="margin-top:1.5rem"><table class="admin-table"><thead><tr><th>Nombre</th><th>Cargo</th><th>Orden</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($items as $item): ?><tr><td><?= e($item['nombre']) ?></td><td><?= e($item['cargo']) ?></td><td><?= e((string) $item['orden']) ?></td><td><a class="btn btn--small" href="<?= e(url('admin/equipo.php?id=' . $item['id'])) ?>">Editar</a><form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) $item['id']) ?>"><button class="btn btn--small" name="action" value="delete">Eliminar</button></form></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

