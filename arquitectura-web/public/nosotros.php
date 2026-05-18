<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$pageTitle = 'Nosotros | ' . setting('site_name', SITE_NAME);
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
  <div class="container section-header" data-reveal>
    <p class="section-kicker">Nosotros</p>
    <div>
      <h1 class="section-title">Arquitectura con memoria, ciudad y futuro.</h1>
      <p class="section-copy">Somos un estudio independiente de arquitectura, urbanismo e interiorismo. Trabajamos con una mirada humana: escuchamos necesidades, leemos el contexto y convertimos restricciones reales en oportunidades de diseño.</p>
    </div>
  </div>
</section>
<section class="section section--tight">
  <div class="container grid grid--3">
    <article class="panel" data-reveal><p class="section-kicker">Mision</p><p>Diseñar espacios funcionales, bellos y responsables que mejoren la vida cotidiana de las personas y fortalezcan su relacion con el entorno.</p></article>
    <article class="panel" data-reveal><p class="section-kicker">Vision</p><p>Ser un estudio referente por integrar sensibilidad ambiental, claridad tecnica y creatividad aplicada a proyectos de distintas escalas.</p></article>
    <article class="panel" data-reveal><p class="section-kicker">Valores</p><p>Escucha, honestidad constructiva, precision, sostenibilidad, colaboracion y respeto por la memoria del lugar.</p></article>
  </div>
</section>
<section class="section">
  <div class="container split">
    <div data-reveal>
      <h2 class="section-title">Historia breve</h2>
    </div>
    <div data-reveal>
      <p class="section-copy">El estudio nace de la union entre practica profesional, investigacion y obra. Desde sus primeros encargos asumio que cada proyecto necesita una respuesta propia: no hay formula unica para una familia, una marca, una comunidad o una ciudad.</p>
      <p class="section-copy">Nuestro enfoque combina analisis, dibujo, conversacion y supervision cercana. La arquitectura aparece como una herramienta para ordenar deseos, recursos, tiempo y materia.</p>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>

