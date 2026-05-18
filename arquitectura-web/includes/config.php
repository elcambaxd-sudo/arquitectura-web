<?php
declare(strict_types=1);

define('DB_HOST', 'localhost');
define('DB_NAME', 'arquitectura_web');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', '/arquitectura-web');
define('UPLOAD_DIR', dirname(__DIR__) . '/assets/uploads');
define('UPLOAD_URL', BASE_URL . '/assets/uploads');
define('SITE_NAME', 'Estudio Raiz');
define('SITE_EMAIL', 'contacto@estudioraiz.test');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

date_default_timezone_set('America/Lima');

