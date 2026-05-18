<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$pageTitle = setting('site_name', SITE_NAME) . ' | Portafolio de arquitectura';
$pageDescription = 'Portada visual con proyectos de arquitectura, urbanismo e interiorismo.';
$homeShowcase = true;

$slides = fetch_all(
    'SELECT p.*, c.nombre AS categoria
     FROM proyectos p
     JOIN categorias_proyecto c ON c.id = p.categoria_id
     WHERE p.publicado = 1
     ORDER BY p.destacado DESC, p.orden ASC, p.id ASC
     LIMIT 12'
);

if (!$slides) {
    $slides = [[
        'titulo' => 'Proyecto en preparacion',
        'slug' => 'proyectos',
        'categoria' => 'Portafolio',
        'ubicacion' => 'Estudio',
        'anio' => date('Y'),
        'imagen_principal' => null,
    ]];
}

include __DIR__ . '/../includes/header.php';
?>
<section class="home-showcase" data-home-gallery aria-label="Portada de proyectos">
  <div class="home-showcase__slides" aria-live="polite">
    <?php foreach ($slides as $index => $project): ?>
      <?php
        $projectUrl = !empty($project['slug']) && $project['slug'] !== 'proyectos'
            ? url('proyectos/' . $project['slug'])
            : url('proyectos');
      ?>
      <article
        class="home-slide <?= $index === 0 ? 'is-active' : '' ?>"
        data-home-slide-panel
        data-title="<?= e($project['titulo']) ?>"
        data-category="<?= e($project['categoria'] ?? 'Proyecto') ?>"
        data-location="<?= e(($project['ubicacion'] ?? 'Ubicacion por definir') . ' / ' . ($project['anio'] ?? date('Y'))) ?>"
        data-url="<?= e($projectUrl) ?>"
        aria-hidden="<?= $index === 0 ? 'false' : 'true' ?>"
      >
        <img src="<?= e(upload_url($project['imagen_principal'] ?? null)) ?>" alt="<?= e($project['titulo']) ?>" loading="<?= $index === 0 ? 'eager' : 'lazy' ?>">
      </article>
    <?php endforeach; ?>
  </div>

  <div class="home-project-bar" aria-label="Seleccion de proyectos">
    <a class="home-brand" href="<?= e(url()) ?>" aria-label="<?= e(setting('site_name', SITE_NAME)) ?>">
      <span>RZ</span>
      <strong><?= e(setting('site_name', SITE_NAME)) ?></strong>
    </a>
    <div class="home-project-tabs" role="tablist" aria-label="Proyectos de portada">
      <?php foreach ($slides as $index => $project): ?>
        <button
          class="home-project-tab <?= $index === 0 ? 'is-active' : '' ?>"
          type="button"
          data-home-slide="<?= $index ?>"
          aria-label="Ver <?= e($project['titulo']) ?>"
          aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"
          role="tab"
        >
          <span><?= e($project['titulo']) ?></span>
          <small><?= e($project['categoria'] ?? 'Proyecto') ?></small>
        </button>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="home-slide-caption">
    <p data-home-category><?= e($slides[0]['categoria'] ?? 'Proyecto') ?></p>
    <a data-home-link class="home-slide-title" href="<?= e(!empty($slides[0]['slug']) && $slides[0]['slug'] !== 'proyectos' ? url('proyectos/' . $slides[0]['slug']) : url('proyectos')) ?>">
      <?= e($slides[0]['titulo']) ?>
    </a>
    <span data-home-location><?= e(($slides[0]['ubicacion'] ?? 'Ubicacion por definir') . ' / ' . ($slides[0]['anio'] ?? date('Y'))) ?></span>
  </div>

  <div class="home-controls" aria-label="Controles del carrusel">
    <button class="home-arrow home-arrow--prev" type="button" data-home-prev aria-label="Proyecto anterior">
      <span></span>
    </button>
    <button class="home-arrow home-arrow--next" type="button" data-home-next aria-label="Proyecto siguiente">
      <span></span>
    </button>
  </div>

  <nav class="home-bottom-nav" aria-label="Secciones principales">
    <a href="<?= e(url('proyectos')) ?>">Proyectos</a>
    <a href="<?= e(url('nosotros')) ?>">Nosotros</a>
    <a href="<?= e(url('servicios')) ?>">Servicios</a>
    <a href="<?= e(url('equipo')) ?>">Equipo</a>
    <a href="<?= e(url('metodo')) ?>">Metodo</a>
    <a href="<?= e(url('media')) ?>">Media</a>
    <a href="<?= e(url('contacto')) ?>">Contacto</a>
  </nav>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
