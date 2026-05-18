<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        execute_query('DELETE FROM proyectos WHERE id = ?', [$id]);
        flash('success', 'Proyecto eliminado.');
    }
    if ($action === 'toggle') {
        execute_query('UPDATE proyectos SET publicado = 1 - publicado WHERE id = ?', [$id]);
        flash('success', 'Estado actualizado.');
    }
    redirect_to('admin/proyectos.php');
}

$layout = 'admin';
$pageTitle = 'Proyectos | Admin';
$projects = fetch_all('SELECT p.*, c.nombre AS categoria FROM proyectos p JOIN categorias_proyecto c ON c.id = p.categoria_id ORDER BY p.orden, p.id DESC');
include __DIR__ . '/../includes/header.php';
?>
<div class="admin-top">
  <div><p class="section-kicker">Contenido</p><h1>Proyectos</h1></div>
  <a class="btn btn--dark" href="<?= e(url('admin/proyecto-form.php')) ?>">Crear proyecto</a>
</div>
<?php if ($message = flash('success')): ?><div class="alert alert--success"><?= e($message) ?></div><?php endif; ?>
<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Proyecto</th><th>Categoria</th><th>Año</th><th>Estado</th><th>Acciones</th></tr></thead>
    <tbody>
      <?php foreach ($projects as $project): ?>
        <tr>
          <td><strong><?= e($project['titulo']) ?></strong><br><span class="badge"><?= e($project['slug']) ?></span></td>
          <td><?= e($project['categoria']) ?></td>
          <td><?= e((string) $project['anio']) ?></td>
          <td><?= $project['publicado'] ? 'Publicado' : 'Oculto' ?><?= $project['destacado'] ? ' · Destacado' : '' ?></td>
          <td>
            <a class="btn btn--small" href="<?= e(url('admin/proyecto-form.php?id=' . $project['id'])) ?>">Editar</a>
            <form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) $project['id']) ?>"><button class="btn btn--small" name="action" value="toggle">Publicar/ocultar</button></form>
            <form method="post" style="display:inline" onsubmit="return confirm('¿Eliminar proyecto?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e((string) $project['id']) ?>"><button class="btn btn--small" name="action" value="delete">Eliminar</button></form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

