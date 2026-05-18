<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$pageTitle = 'Proyectos | ' . setting('site_name', SITE_NAME);
$pageDescription = 'Portafolio de proyectos arquitectonicos con filtros por categoria.';
$categories = fetch_all('SELECT * FROM categorias_proyecto WHERE estado = 1 ORDER BY orden');
$projects = fetch_all('SELECT p.*, c.nombre AS categoria, c.slug AS categoria_slug FROM proyectos p JOIN categorias_proyecto c ON c.id = p.categoria_id WHERE p.publicado = 1 ORDER BY p.orden, p.fecha_creacion DESC');
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-header" data-reveal>
      <p class="section-kicker">Portafolio</p>
      <div>
        <h1 class="section-title">Proyectos</h1>
        <p class="section-copy">Filtra por categoria o busca por nombre, ubicacion, año o tipo de proyecto.</p>
      </div>
    </div>
    <div class="filter-panel" data-reveal>
      <input class="filter-input" data-project-search type="search" placeholder="Buscar proyectos">
      <div class="filter-buttons" aria-label="Filtros de proyectos">
        <button class="filter-button is-active" type="button" data-project-filter="all">Todos</button>
        <?php foreach ($categories as $category): ?>
          <button class="filter-button" type="button" data-project-filter="<?= e($category['slug']) ?>"><?= e($category['nombre']) ?></button>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="empty-state" data-empty-projects>No hay proyectos con ese filtro. Prueba otra categoria o busqueda.</div>
    <div class="grid grid--3" style="margin-top:2rem">
      <?php foreach ($projects as $project): ?>
        <article
          class="project-card"
          data-reveal
          data-project-card
          data-category="<?= e($project['categoria_slug']) ?>"
          data-search="<?= e($project['titulo'] . ' ' . $project['ubicacion'] . ' ' . $project['anio'] . ' ' . $project['categoria'] . ' ' . $project['descripcion_corta']) ?>"
        >
          <a href="<?= e(url('proyectos/' . $project['slug'])) ?>">
            <div class="project-card__image ratio-img">
              <img src="<?= e(upload_url($project['imagen_principal'])) ?>" alt="<?= e($project['titulo']) ?>">
              <div class="project-card__overlay">Ver mas →</div>
            </div>
            <div class="project-card__body">
              <span class="project-card__meta"><?= e($project['categoria']) ?> · <?= e((string) $project['anio']) ?></span>
              <h3><?= e($project['titulo']) ?></h3>
              <p><strong><?= e($project['ubicacion']) ?></strong></p>
              <p><?= e($project['descripcion_corta']) ?></p>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>

