<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return $needle === '' || strpos($haystack, $needle) === 0;
    }
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function upload_url(?string $path): string
{
    if (!$path) {
        return asset('img/placeholder.svg');
    }
    if (str_starts_with($path, 'http')) {
        return $path;
    }
    return UPLOAD_URL . '/' . ltrim($path, '/');
}

function current_path(): string
{
    $uri = strtok($_SERVER['REQUEST_URI'] ?? '', '?') ?: '';
    return trim(str_replace(BASE_URL, '', $uri), '/');
}

function is_active(string $segment): string
{
    return str_starts_with(current_path(), trim($segment, '/')) ? 'is-active' : '';
}

function slugify(string $text): string
{
    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text) ?: $text;
    $text = preg_replace('~[^\\pL\\d]+~u', '-', $text) ?? $text;
    $text = trim($text, '-');
    $text = strtolower($text);
    $text = preg_replace('~[^-a-z0-9]+~', '', $text) ?? $text;
    return $text ?: 'item-' . time();
}

function excerpt(string $text, int $limit = 150): string
{
    $clean = trim(strip_tags($text));
    if (mb_strlen($clean) <= $limit) {
        return $clean;
    }
    return mb_substr($clean, 0, $limit - 1) . '...';
}

function redirect_to(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function flash(string $key, ?string $message = null): ?string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $value;
}

function safe_upload(array $file, string $folder): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('No se pudo subir la imagen.');
    }
    if (($file['size'] ?? 0) > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('La imagen supera el tamano permitido de 5MB.');
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Formato no permitido. Usa jpg, png o webp.');
    }

    $safeFolder = trim($folder, '/');
    $targetDir = UPLOAD_DIR . '/' . $safeFolder;
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }

    $name = bin2hex(random_bytes(10)) . '.' . $allowed[$mime];
    $target = $targetDir . '/' . $name;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new RuntimeException('No se pudo guardar la imagen.');
    }

    return $safeFolder . '/' . $name;
}

function setting(string $key, string $default = ''): string
{
    static $settings = null;
    if ($settings === null) {
        require_once __DIR__ . '/db.php';
        $settings = [];
        try {
            foreach (fetch_all('SELECT clave, valor FROM configuracion_sitio') as $row) {
                $settings[$row['clave']] = $row['valor'];
            }
        } catch (Throwable $error) {
            $settings = [];
        }
    }
    return $settings[$key] ?? $default;
}
