<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_admin();

$id = (int) ($_GET['id'] ?? 0);
$project = $id ? fetch_one('SELECT * FROM proyectos WHERE id = ?', [$id]) : null;
$categories = fetch_all('SELECT * FROM categorias_proyecto WHERE estado = 1 ORDER BY orden');
$gallery = $id ? fetch_all('SELECT * FROM galeria_proyectos WHERE proyecto_id = ? ORDER BY orden', [$id]) : [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $title = trim($_POST['titulo'] ?? '');
        $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
        $categoria = (int) ($_POST['categoria_id'] ?? 0);
        if ($title === '' || !$categoria) {
            throw new RuntimeException('Titulo y categoria son obligatorios.');
        }
        $image = $project['imagen_principal'] ?? null;
        if (!empty($_FILES['imagen_principal']['name'])) {
            $image = safe_upload($_FILES['imagen_principal'], 'projects');
        }
        $data = [
            $title,
            $slug,
            $categoria,
            trim($_POST['ubicacion'] ?? ''),
            (int) ($_POST['anio'] ?? date('Y')),
            trim($_POST['area'] ?? ''),
            trim($_POST['cliente'] ?? ''),
            trim($_POST['estado_proyecto'] ?? ''),
            trim($_POST['descripcion_corta'] ?? ''),
            trim($_POST['descripcion_larga'] ?? ''),
            trim($_POST['enfoque_diseno'] ?? ''),
            trim($_POST['sostenibilidad'] ?? ''),
            $image,
            isset($_POST['destacado']) ? 1 : 0,
            isset($_POST['publicado']) ? 1 : 0,
            (int) ($_POST['orden'] ?? 0),
        ];
        if ($project) {
            execute_query('UPDATE proyectos SET titulo=?, slug=?, categoria_id=?, ubicacion=?, anio=?, area=?, cliente=?, estado_proyecto=?, descripcion_corta=?, descripcion_larga=?, enfoque_diseno=?, sostenibilidad=?, imagen_principal=?, destacado=?, publicado=?, orden=? WHERE id=?', [...$data, $id]);
            $projectId = $id;
        } else {
            execute_query('INSERT INTO proyectos (titulo, slug, categoria_id, ubicacion, anio, area, cliente, estado_proyecto, descripcion_corta, descripcion_larga, enfoque_diseno, sostenibilidad, imagen_principal, destacado, publicado, orden) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $data);
            $projectId = (int) db()->lastInsertId();
        }

        if (!empty($_FILES['galeria']['name'][0])) {
            foreach ($_FILES['galeria']['name'] as $index => $name) {
                $file = [
                    'name' => $_FILES['galeria']['name'][$index],
                    'type' => $_FILES['galeria']['type'][$index],
                    'tmp_name' => $_FILES['galeria']['tmp_name'][$index],
                    'error' => $_FILES['galeria']['error'][$index],
                    'size' => $_FILES['galeria']['size'][$index],
                ];
                $uploaded = safe_upload($file, 'projects');
                execute_query('INSERT INTO galeria_proyectos (proyecto_id, imagen, alt_text, orden) VALUES (?, ?, ?, ?)', [$projectId, $uploaded, $title, $index + 50]);
            }
        }

        flash('success', 'Proyecto guardado.');
        redirect_to('admin/proyectos.php');
    } catch (Throwable $error) {
        $errors[] = $error->getMessage();
    }
}

$layout = 'admin';
$pageTitle = ($project ? 'Editar proyecto' : 'Nuevo proyecto') . ' | Admin';
include __DIR__ . '/../includes/header.php';
?>
<div class="admin-top"><div><p class="section-kicker">Proyectos</p><h1><?= $project ? 'Editar proyecto' : 'Nuevo proyecto' ?></h1></div><a class="btn" href="<?= e(url('admin/proyectos.php')) ?>">Volver</a></div>
<?php foreach ($errors as $error): ?><div class="alert alert--error"><?= e($error) ?></div><?php endforeach; ?>
<form class="admin-card admin-form" method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div class="two">
    <label class="field">Titulo<input class="form-control" name="titulo" value="<?= e($project['titulo'] ?? '') ?>" required></label>
    <label class="field">Slug<input class="form-control" name="slug" value="<?= e($project['slug'] ?? '') ?>" placeholder="Se genera automaticamente"></label>
  </div>
  <div class="three">
    <label class="field">Categoria<select class="form-control" name="categoria_id" required><?php foreach ($categories as $category): ?><option value="<?= e((string) $category['id']) ?>" <?= (($project['categoria_id'] ?? '') == $category['id']) ? 'selected' : '' ?>><?= e($category['nombre']) ?></option><?php endforeach; ?></select></label>
    <label class="field">Ubicacion<input class="form-control" name="ubicacion" value="<?= e($project['ubicacion'] ?? '') ?>"></label>
    <label class="field">Año<input class="form-control" type="number" name="anio" value="<?= e((string) ($project['anio'] ?? date('Y'))) ?>"></label>
  </div>
  <div class="three">
    <label class="field">Area<input class="form-control" name="area" value="<?= e($project['area'] ?? '') ?>"></label>
    <label class="field">Cliente<input class="form-control" name="cliente" value="<?= e($project['cliente'] ?? '') ?>"></label>
    <label class="field">Estado<input class="form-control" name="estado_proyecto" value="<?= e($project['estado_proyecto'] ?? '') ?>"></label>
  </div>
  <label class="field">Descripcion corta<textarea class="form-control" name="descripcion_corta" rows="3"><?= e($project['descripcion_corta'] ?? '') ?></textarea></label>
  <label class="field">Descripcion larga<textarea class="form-control" name="descripcion_larga" rows="6"><?= e($project['descripcion_larga'] ?? '') ?></textarea></label>
  <div class="two">
    <label class="field">Enfoque de diseño<textarea class="form-control" name="enfoque_diseno" rows="5"><?= e($project['enfoque_diseno'] ?? '') ?></textarea></label>
    <label class="field">Sostenibilidad<textarea class="form-control" name="sostenibilidad" rows="5"><?= e($project['sostenibilidad'] ?? '') ?></textarea></label>
  </div>
  <div class="three">
    <label class="field">Imagen principal<input class="form-control" type="file" name="imagen_principal" accept=".jpg,.jpeg,.png,.webp"></label>
    <label class="field">Galeria<input class="form-control" type="file" name="galeria[]" accept=".jpg,.jpeg,.png,.webp" multiple></label>
    <label class="field">Orden<input class="form-control" type="number" name="orden" value="<?= e((string) ($project['orden'] ?? 0)) ?>"></label>
  </div>
  <label><input type="checkbox" name="destacado" <?= !empty($project['destacado']) ? 'checked' : '' ?>> Destacado</label>
  <label><input type="checkbox" name="publicado" <?= ($project['publicado'] ?? 1) ? 'checked' : '' ?>> Publicado</label>
  <?php if ($gallery): ?><p class="badge"><?= count($gallery) ?> imagenes en galeria</p><?php endif; ?>
  <button class="btn btn--dark" type="submit">Guardar proyecto</button>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>

