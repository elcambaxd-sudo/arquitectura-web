<?php
declare(strict_types=1);

$layout = $layout ?? 'public';
?>
<?php if ($layout === 'public'): ?>
  </main>
  <footer class="site-footer">
    <div>
      <p class="footer-kicker">Estudio de arquitectura</p>
      <h2><?= e(setting('site_tagline', 'Diseñamos espacios que permanecen, se adaptan y dialogan con su entorno.')) ?></h2>
    </div>
    <div class="footer-grid">
      <div>
        <h3>Navegacion</h3>
        <a href="<?= e(url('proyectos')) ?>">Proyectos</a>
        <a href="<?= e(url('servicios')) ?>">Servicios</a>
        <a href="<?= e(url('metodo')) ?>">Metodo</a>
        <a href="<?= e(url('media')) ?>">Media</a>
      </div>
      <div>
        <h3>Contacto</h3>
        <a href="mailto:<?= e(setting('contact_email', SITE_EMAIL)) ?>"><?= e(setting('contact_email', SITE_EMAIL)) ?></a>
        <span><?= e(setting('contact_phone', '+51 999 000 000')) ?></span>
        <span><?= e(setting('contact_address', 'Av. Central 245, Lima')) ?></span>
      </div>
      <div>
        <h3>Redes</h3>
        <a href="#">Instagram</a>
        <a href="#">LinkedIn</a>
        <a href="#">YouTube</a>
      </div>
    </div>
    <p class="copyright">© <?= date('Y') ?> <?= e(setting('site_name', SITE_NAME)) ?>. Todos los derechos reservados.</p>
  </footer>
<?php else: ?>
    </main>
  </div>
<?php endif; ?>
<script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>

