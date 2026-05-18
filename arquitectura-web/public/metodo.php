<?php
require_once __DIR__ . '/../includes/helpers.php';

$pageTitle = 'Metodo | ' . setting('site_name', SITE_NAME);
$steps = [
  ['Escuchar y entender al cliente', 'Definimos necesidades, expectativas, tiempos, recursos y prioridades reales.'],
  ['Analizar el contexto', 'Leemos clima, normativa, accesos, memoria material, entorno urbano y oportunidades.'],
  ['Conceptualizar la propuesta', 'Construimos una idea rectora capaz de ordenar programa, forma y experiencia.'],
  ['Diseñar soluciones', 'Desarrollamos alternativas claras, evaluables y alineadas con el presupuesto.'],
  ['Documentar tecnicamente', 'Preparamos planos, especificaciones, metrados y criterios para construir.'],
  ['Coordinar especialidades', 'Integramos estructura, instalaciones, costos y permisos.'],
  ['Supervisar ejecucion', 'Acompañamos obra para resolver interferencias y proteger la calidad.'],
  ['Entregar y acompañar', 'Cerramos el proceso con seguimiento, ajustes y recomendaciones de uso.'],
];
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-header" data-reveal>
      <p class="section-kicker">Metodo</p>
      <div>
        <h1 class="section-title">Proceso claro, decisiones visibles.</h1>
        <p class="section-copy">Trabajamos por etapas para que el proyecto avance con informacion, criterio y trazabilidad.</p>
      </div>
    </div>
    <div class="timeline">
      <?php foreach ($steps as $step): ?>
        <article class="method-card" data-reveal>
          <div>
            <h2><?= e($step[0]) ?></h2>
            <p><?= e($step[1]) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>

