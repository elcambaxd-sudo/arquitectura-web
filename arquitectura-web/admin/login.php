<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

if (current_admin()) {
    redirect_to('admin/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    if (attempt_login($email, $password)) {
        redirect_to('admin/dashboard.php');
    }
    $error = 'Credenciales invalidas o usuario inactivo.';
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | <?= e(SITE_NAME) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/styles.css')) ?>">
</head>
<body class="login-page">
  <form class="login-card admin-form" method="post">
    <?= csrf_field() ?>
    <div>
      <p class="section-kicker">Panel de administracion</p>
      <h1>Ingresar</h1>
      <p>Admin inicial: admin@demo.com / Admin123456</p>
    </div>
    <?php if ($error): ?><div class="alert alert--error"><?= e($error) ?></div><?php endif; ?>
    <label class="field">Email<input class="form-control" type="email" name="email" required></label>
    <label class="field">Contraseña<input class="form-control" type="password" name="password" required></label>
    <button class="btn btn--dark" type="submit">Entrar</button>
  </form>
</body>
</html>

