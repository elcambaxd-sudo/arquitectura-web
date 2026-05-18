<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$pageTitle = 'Equipo | ' . setting('site_name', SITE_NAME);
$team = fetch_all('SELECT * FROM equipo WHERE estado = 1 ORDER BY orden');
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-header" data-reveal>
      <p class="section-kicker">Equipo</p>
      <div>
        <h1 class="section-title">Un equipo que une idea, tecnica y obra.</h1>
        <p class="section-copy">Perfiles complementarios para diseñar, documentar, visualizar y construir con coherencia.</p>
      </div>
    </div>
    <div class="grid grid--4">
      <?php foreach ($team as $member): ?>
        <article class="team-card" data-reveal>
          <div class="team-card__photo ratio-img">
            <img src="<?= e(upload_url($member['foto'])) ?>" alt="<?= e($member['nombre']) ?>">
          </div>
          <p class="project-card__meta"><?= e($member['especialidad']) ?></p>
          <h2><?= e($member['nombre']) ?></h2>
          <p><strong><?= e($member['cargo']) ?></strong></p>
          <p><?= e($member['biografia']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>

