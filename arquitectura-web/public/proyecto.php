<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$slug = $_GET['slug'] ?? '';
$project = fetch_one('SELECT p.*, c.nombre AS categoria, c.slug AS categoria_slug FROM proyectos p JOIN categorias_proyecto c ON c.id = p.categoria_id WHERE p.slug = ? AND p.publicado = 1', [$slug]);

if (!$project) {
    http_response_code(404);
    $pageTitle = 'Proyecto no encontrado | ' . setting('site_name', SITE_NAME);
    include __DIR__ . '/../includes/header.php';
    echo '<section class="section"><div class="container panel"><h1>Proyecto no encontrado</h1><p>El proyecto solicitado no esta publicado o cambio de ubicacion.</p><a class="btn btn--dark" href="' . e(url('proyectos')) . '">Volver a proyectos</a></div></section>';
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$gallery = fetch_all('SELECT * FROM galeria_proyectos WHERE proyecto_id = ? ORDER BY orden', [$project['id']]);
$prev = fetch_one('SELECT slug, titulo FROM proyectos WHERE publicado = 1 AND orden < ? ORDER BY orden DESC LIMIT 1', [$project['orden']]);
$next = fetch_one('SELECT slug, titulo FROM proyectos WHERE publicado = 1 AND orden > ? ORDER BY orden ASC LIMIT 1', [$project['orden']]);
$pageTitle = $project['titulo'] . ' | ' . setting('site_name', SITE_NAME);
$pageDescription = $project['descripcion_corta'];
include __DIR__ . '/../includes/header.php';
?>
<article class="container project-hero">
  <div data-reveal>
    <p class="section-kicker"><?= e($project['categoria']) ?> · <?= e($project['ubicacion']) ?></p>
    <h1 class="section-title"><?= e($project['titulo']) ?></h1>
    <p class="section-copy"><?= e($project['descripcion_corta']) ?></p>
  </div>
  <div class="project-hero__image ratio-img" data-reveal>
    <img src="<?= e(upload_url($project['imagen_principal'])) ?>" alt="<?= e($project['titulo']) ?>">
  </div>
</article>

<section class="section">
  <div class="container split">
    <div data-reveal>
      <h2>Descripcion conceptual</h2>
      <p class="section-copy"><?= nl2br(e($project['descripcion_larga'])) ?></p>
    </div>
    <aside class="panel" data-reveal>
      <h2>Datos tecnicos</h2>
      <dl class="details-list">
        <div><dt>Cliente</dt><dd><?= e($project['cliente']) ?></dd></div>
        <div><dt>Ubicacion</dt><dd><?= e($project['ubicacion']) ?></dd></div>
        <div><dt>Año</dt><dd><?= e((string) $project['anio']) ?></dd></div>
        <div><dt>Area</dt><dd><?= e($project['area']) ?></dd></div>
        <div><dt>Estado</dt><dd><?= e($project['estado_proyecto']) ?></dd></div>
        <div><dt>Categoria</dt><dd><?= e($project['categoria']) ?></dd></div>
        <div><dt>Equipo</dt><dd><?= e(setting('site_name', SITE_NAME)) ?></dd></div>
      </dl>
    </aside>
  </div>
</section>

<?php if ($gallery): ?>
<section class="section section--tight">
  <div class="container">
    <div class="section-header" data-reveal>
      <p class="section-kicker">Galeria</p>
      <h2 class="section-title">Imagenes del proyecto</h2>
    </div>
    <div class="gallery">
      <?php foreach ($gallery as $image): ?>
        <div class="ratio-img" data-reveal>
          <img src="<?= e(upload_url($image['imagen'])) ?>" alt="<?= e($image['alt_text'] ?: $project['titulo']) ?>">
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container grid grid--2 split">
    <div class="panel" data-reveal>
      <p class="section-kicker">Enfoque del diseño</p>
      <p class="section-copy"><?= nl2br(e($project['enfoque_diseno'])) ?></p>
    </div>
    <div class="panel" data-reveal>
      <p class="section-kicker">Sostenibilidad y entorno</p>
      <p class="section-copy"><?= nl2br(e($project['sostenibilidad'])) ?></p>
    </div>
  </div>
</section>

<nav class="container button-row section--tight" aria-label="Navegacion de proyectos">
  <?php if ($prev): ?><a class="btn" href="<?= e(url('proyectos/' . $prev['slug'])) ?>">← <?= e($prev['titulo']) ?></a><?php endif; ?>
  <?php if ($next): ?><a class="btn btn--dark" href="<?= e(url('proyectos/' . $next['slug'])) ?>"><?= e($next['titulo']) ?> →</a><?php endif; ?>
</nav>
<?php include __DIR__ . '/../includes/footer.php'; ?>

