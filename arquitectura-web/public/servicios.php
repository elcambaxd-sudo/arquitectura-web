<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$pageTitle = 'Servicios | ' . setting('site_name', SITE_NAME);
$services = fetch_all('SELECT * FROM servicios WHERE estado = 1 ORDER BY orden');
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-header" data-reveal>
      <p class="section-kicker">Servicios</p>
      <div>
        <h1 class="section-title">Una practica integral para cada etapa del proyecto.</h1>
        <p class="section-copy">Desde la primera conversacion hasta la entrega, cada servicio aporta claridad y control.</p>
      </div>
    </div>
    <div class="grid grid--4">
      <?php foreach ($services as $service): ?>
        <article class="service-card" data-reveal>
          <div class="service-card__icon"><?= e(strtoupper(substr($service['titulo'], 0, 2))) ?></div>
          <h2><?= e($service['titulo']) ?></h2>
          <p><?= e($service['descripcion']) ?></p>
          <p><strong>Beneficio:</strong> <?= e($service['beneficio_cliente']) ?></p>
          <a class="btn btn--small" href="<?= e(url('proyectos')) ?>">Ver proyectos</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>

