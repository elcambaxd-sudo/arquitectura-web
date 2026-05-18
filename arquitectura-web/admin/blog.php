<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = (int) ($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'delete') {
        execute_query('DELETE FROM articulos WHERE id = ?', [$id]);
        flash('success', 'Articulo eliminado.');
    } else {
        $current = $id ? fetch_one('SELECT * FROM articulos WHERE id=?', [$id]) : null;
        $img = $current['imagen_destacada'] ?? null;
        if (!empty($_FILES['imagen_destacada']['name'])) $img = safe_upload($_FILES['imagen_destacada'], 'blog');
        $titulo = trim($_POST['titulo'] ?? '');
        $slug = trim($_POST['slug'] ?? '') ?: slugify($titulo);
        $params = [$titulo, $slug, trim($_POST['categoria'] ?? ''), trim($_POST['autor'] ?? ''), $_POST['fecha_publicacion'] ?: date('Y-m-d'), $img, trim($_POST['resumen'] ?? ''), trim($_POST['contenido'] ?? ''), isset($_POST['publicado']) ? 1 : 0];
        if ($id) execute_query('UPDATE articulos SET titulo=?, slug=?, categoria=?, autor=?, fecha_publicacion=?, imagen_destacada=?, resumen=?, contenido=?, publicado=? WHERE id=?', [...$params, $id]);
        else execute_query('INSERT INTO articulos (titulo, slug, categoria, autor, fecha_publicacion, imagen_destacada, resumen, contenido, publicado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', $params);
        flash('success', 'Articulo guardado.');
    }
    redirect_to('admin/blog.php');
}

$edit = isset($_GET['id']) ? fetch_one('SELECT * FROM articulos WHERE id = ?', [(int) $_GET['id']]) : null;
$items = fetch_all('SELECT * FROM articulos ORDER BY fecha_publicacion DESC, id DESC');
$layout = 'admin';
$pageTitle = 'Media | Admin';
include __DIR__ . '/../includes/header.php';
?>
<div class="admin-top"><div><p class="section-kicker">Contenido</p><h1>Media / Blog</h1></div></div>
<?php if ($message = flash('success')): ?><div class="alert alert--success"><?= e($message) ?></div><?php endif; ?>
<form class="admin-card admin-form" method="post" enctype="multipart/form-data">
  <?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) ($edit['id'] ?? 0)) ?>">
  <div class="three"><label class="field">Titulo<input class="form-control" name="titulo" value="<?= e($edit['titulo'] ?? '') ?>" required></label><label class="field">Slug<input class="form-control" name="slug" value="<?= e($edit['slug'] ?? '') ?>"></label><label class="field">Categoria<input class="form-control" name="categoria" value="<?= e($edit['categoria'] ?? 'Arquitectura') ?>"></label></div>
  <div class="three"><label class="field">Autor<input class="form-control" name="autor" value="<?= e($edit['autor'] ?? 'Equipo editorial') ?>"></label><label class="field">Fecha<input class="form-control" type="date" name="fecha_publicacion" value="<?= e($edit['fecha_publicacion'] ?? date('Y-m-d')) ?>"></label><label class="field">Imagen<input class="form-control" type="file" name="imagen_destacada" accept=".jpg,.jpeg,.png,.webp"></label></div>
  <label class="field">Resumen<textarea class="form-control" name="resumen" rows="3"><?= e($edit['resumen'] ?? '') ?></textarea></label>
  <label class="field">Contenido<textarea class="form-control" name="contenido" rows="8"><?= e($edit['contenido'] ?? '') ?></textarea></label>
  <label><input type="checkbox" name="publicado" <?= ($edit['publicado'] ?? 1) ? 'checked' : '' ?>> Publicado</label>
  <button class="btn btn--dark" type="submit">Guardar articulo</button>
</form>
<div class="admin-table-wrap" style="margin-top:1.5rem"><table class="admin-table"><thead><tr><th>Titulo</th><th>Categoria</th><th>Fecha</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($items as $item): ?><tr><td><?= e($item['titulo']) ?></td><td><?= e($item['categoria']) ?></td><td><?= e($item['fecha_publicacion']) ?></td><td><a class="btn btn--small" href="<?= e(url('admin/blog.php?id=' . $item['id'])) ?>">Editar</a><form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) $item['id']) ?>"><button class="btn btn--small" name="action" value="delete">Eliminar</button></form></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

