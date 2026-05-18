<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    try {
        verify_csrf();
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $tipo = trim($_POST['tipo_proyecto'] ?? '');
        $presupuesto = trim($_POST['presupuesto_estimado'] ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');

        if ($nombre === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $tipo === '' || strlen($mensaje) < 10) {
            throw new RuntimeException('Completa los campos obligatorios con datos validos.');
        }

        execute_query(
            'INSERT INTO mensajes_contacto (nombre, email, telefono, tipo_proyecto, presupuesto_estimado, mensaje) VALUES (?, ?, ?, ?, ?, ?)',
            [$nombre, $email, $telefono, $tipo, $presupuesto, $mensaje]
        );

        // Para enviar correo real, conecta aqui PHPMailer, SMTP, Resend o una API propia.

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true, 'message' => 'Mensaje enviado. Te responderemos pronto.']);
            exit;
        }
        flash('success', 'Mensaje enviado. Te responderemos pronto.');
        redirect_to('contacto');
    } catch (Throwable $error) {
        if ($isAjax) {
            http_response_code(422);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => $error->getMessage()]);
            exit;
        }
        flash('error', $error->getMessage());
        redirect_to('contacto');
    }
}

$pageTitle = 'Contacto | ' . setting('site_name', SITE_NAME);
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
  <div class="container contact-grid">
    <div data-reveal>
      <p class="section-kicker">Contacto</p>
      <h1 class="section-title">Conversemos sobre tu proyecto.</h1>
      <p class="section-copy">Cuéntanos la escala, ubicacion y etapa del encargo. Revisaremos la informacion y responderemos con los siguientes pasos.</p>
      <div class="panel" style="margin-top:2rem">
        <p><strong>Email:</strong> <?= e(setting('contact_email', SITE_EMAIL)) ?></p>
        <p><strong>Telefono:</strong> <?= e(setting('contact_phone', '+51 999 000 000')) ?></p>
        <p><strong>Direccion:</strong> <?= e(setting('contact_address', 'Av. Central 245, Lima')) ?></p>
        <p><strong>Redes:</strong> Instagram · LinkedIn · YouTube</p>
      </div>
    </div>
    <form class="panel form-grid" action="<?= e(url('contacto')) ?>" method="post" data-contact-form data-reveal novalidate>
      <?= csrf_field() ?>
      <div data-form-status>
        <?php if ($message = flash('success')): ?><div class="alert alert--success"><?= e($message) ?></div><?php endif; ?>
        <?php if ($message = flash('error')): ?><div class="alert alert--error"><?= e($message) ?></div><?php endif; ?>
      </div>
      <div class="two">
        <div class="field"><label>Nombre *</label><input class="form-control" name="nombre" required><span class="field-error">Ingresa tu nombre.</span></div>
        <div class="field"><label>Correo *</label><input class="form-control" type="email" name="email" required><span class="field-error">Ingresa un correo valido.</span></div>
      </div>
      <div class="two">
        <div class="field"><label>Telefono</label><input class="form-control" name="telefono"></div>
        <div class="field"><label>Tipo de proyecto *</label><select class="form-control" name="tipo_proyecto" required><option value="">Selecciona</option><option>Vivienda</option><option>Comercial</option><option>Interiorismo</option><option>Urbanismo</option><option>Consultoria</option></select><span class="field-error">Selecciona un tipo.</span></div>
      </div>
      <div class="field"><label>Presupuesto estimado</label><input class="form-control" name="presupuesto_estimado" placeholder="Opcional"></div>
      <div class="field"><label>Mensaje *</label><textarea class="form-control" name="mensaje" rows="6" required></textarea><span class="field-error">Cuéntanos al menos 10 caracteres.</span></div>
      <button class="btn btn--dark" type="submit">Enviar mensaje</button>
    </form>
  </div>
</section>
<section class="section section--tight">
  <div class="container">
    <div class="map-placeholder" data-reveal>
      <div>
        <strong>Mapa referencial</strong>
        <p><?= e(setting('contact_address', 'Av. Central 245, Lima')) ?></p>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>

