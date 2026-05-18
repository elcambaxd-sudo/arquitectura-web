<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

$pageTitle = $pageTitle ?? setting('site_name', SITE_NAME);
$pageDescription = $pageDescription ?? setting('site_description', 'Arquitectura, urbanismo e interiorismo con mirada consciente.');
$layout = $layout ?? 'public';
$bodyClass = $layout === 'admin' ? 'admin-body' : 'public-body';
if (!empty($homeShowcase)) {
    $bodyClass .= ' is-home-showcase';
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($pageDescription) ?>">
  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($pageDescription) ?>">
  <meta property="og:type" content="website">
  <meta property="og:image" content="<?= e(asset('img/og-placeholder.svg')) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/styles.css')) ?>">
</head>
<body class="<?= e($bodyClass) ?>">
<?php if ($layout === 'public'): ?>
  <div class="loader" data-loader>
    <div class="loader__mark">
      <span></span><span></span><span></span>
    </div>
    <p><?= e(setting('site_name', SITE_NAME)) ?></p>
  </div>
  <header class="site-header" data-header>
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <nav class="navbar" aria-label="Navegacion principal">
      <a class="brand" href="<?= e(url()) ?>" aria-label="<?= e(setting('site_name', SITE_NAME)) ?>">
        <span class="brand__iso">RZ</span>
        <span class="brand__text"><?= e(setting('site_name', SITE_NAME)) ?></span>
      </a>
      <button class="nav-toggle" type="button" data-nav-toggle aria-label="Abrir menu" aria-expanded="false">
        <span></span><span></span>
      </button>
      <div class="nav-links" data-nav-menu>
        <a class="<?= e(is_active('proyectos')) ?>" href="<?= e(url('proyectos')) ?>">Proyectos</a>
        <a class="<?= e(is_active('nosotros')) ?>" href="<?= e(url('nosotros')) ?>">Nosotros</a>
        <a class="<?= e(is_active('servicios')) ?>" href="<?= e(url('servicios')) ?>">Servicios</a>
        <a class="<?= e(is_active('equipo')) ?>" href="<?= e(url('equipo')) ?>">Equipo</a>
        <a class="<?= e(is_active('metodo')) ?>" href="<?= e(url('metodo')) ?>">Metodo</a>
        <a class="<?= e(is_active('media')) ?>" href="<?= e(url('media')) ?>">Media</a>
        <a class="nav-cta <?= e(is_active('contacto')) ?>" href="<?= e(url('contacto')) ?>">Contacto</a>
      </div>
    </nav>
  </header>
  <main id="contenido" class="page-transition">
<?php else: ?>
  <div class="admin-shell">
    <aside class="admin-sidebar">
      <a class="admin-brand" href="<?= e(url('admin/dashboard.php')) ?>">
        <span>RZ</span>
        <strong>Admin Raiz</strong>
      </a>
      <nav>
        <a href="<?= e(url('admin/dashboard.php')) ?>">Dashboard</a>
        <a href="<?= e(url('admin/proyectos.php')) ?>">Proyectos</a>
        <a href="<?= e(url('admin/categorias.php')) ?>">Categorias</a>
        <a href="<?= e(url('admin/servicios.php')) ?>">Servicios</a>
        <a href="<?= e(url('admin/equipo.php')) ?>">Equipo</a>
        <a href="<?= e(url('admin/blog.php')) ?>">Media</a>
        <a href="<?= e(url('admin/mensajes.php')) ?>">Mensajes</a>
        <a href="<?= e(url('admin/configuracion.php')) ?>">Configuracion</a>
        <a href="<?= e(url('admin/logout.php')) ?>">Salir</a>
      </nav>
    </aside>
    <main class="admin-main">
<?php endif; ?>
