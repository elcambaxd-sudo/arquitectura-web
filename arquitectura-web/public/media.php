<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$pageTitle = 'Media | ' . setting('site_name', SITE_NAME);
$posts = fetch_all('SELECT * FROM articulos WHERE publicado = 1 ORDER BY fecha_publicacion DESC');
$categories = array_values(array_unique(array_map(fn($post) => $post['categoria'], $posts)));
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-header" data-reveal>
      <p class="section-kicker">Media</p>
      <div>
        <h1 class="section-title">Ideas, procesos y ciudad.</h1>
        <p class="section-copy">Articulos y reflexiones sobre arquitectura, sostenibilidad, interiorismo y practica profesional.</p>
      </div>
    </div>
    <div class="filter-buttons" style="margin-bottom:2rem" data-reveal>
      <button class="filter-button is-active" type="button" data-blog-filter="all">Todos</button>
      <?php foreach ($categories as $category): ?>
        <button class="filter-button" type="button" data-blog-filter="<?= e($category) ?>"><?= e($category) ?></button>
      <?php endforeach; ?>
    </div>
    <div class="grid grid--3">
      <?php foreach ($posts as $post): ?>
        <article class="article-card" data-reveal data-blog-card data-category="<?= e($post['categoria']) ?>">
          <a href="<?= e(url('media/' . $post['slug'])) ?>">
            <div class="article-card__image ratio-img">
              <img src="<?= e(upload_url($post['imagen_destacada'])) ?>" alt="<?= e($post['titulo']) ?>">
            </div>
            <p class="project-card__meta"><?= e($post['categoria']) ?> · <?= e(date('d/m/Y', strtotime($post['fecha_publicacion']))) ?></p>
            <h2><?= e($post['titulo']) ?></h2>
            <p><?= e($post['resumen']) ?></p>
          </a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>

