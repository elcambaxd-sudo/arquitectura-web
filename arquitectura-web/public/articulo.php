<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$slug = $_GET['slug'] ?? '';
$post = fetch_one('SELECT * FROM articulos WHERE slug = ? AND publicado = 1', [$slug]);
if (!$post) {
    http_response_code(404);
    $pageTitle = 'Articulo no encontrado | ' . setting('site_name', SITE_NAME);
    include __DIR__ . '/../includes/header.php';
    echo '<section class="section"><div class="container panel"><h1>Articulo no encontrado</h1><a class="btn btn--dark" href="' . e(url('media')) . '">Volver a media</a></div></section>';
    include __DIR__ . '/../includes/footer.php';
    exit;
}
$pageTitle = $post['titulo'] . ' | ' . setting('site_name', SITE_NAME);
$pageDescription = $post['resumen'];
include __DIR__ . '/../includes/header.php';
?>
<article class="section">
  <div class="container" style="max-width:900px">
    <p class="section-kicker" data-reveal><?= e($post['categoria']) ?> · <?= e(date('d/m/Y', strtotime($post['fecha_publicacion']))) ?></p>
    <h1 class="section-title" data-reveal><?= e($post['titulo']) ?></h1>
    <p class="section-copy" data-reveal>Por <?= e($post['autor']) ?></p>
    <div class="project-hero__image ratio-img" data-reveal style="margin:2rem 0">
      <img src="<?= e(upload_url($post['imagen_destacada'])) ?>" alt="<?= e($post['titulo']) ?>">
    </div>
    <div class="section-copy" data-reveal><?= nl2br(e($post['contenido'])) ?></div>
  </div>
</article>
<?php include __DIR__ . '/../includes/footer.php'; ?>

