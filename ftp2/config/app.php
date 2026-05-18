<?php

session_start();


define('DB_HOST', 'localhost');
define('DB_NAME', 'ftp_media_mvc');
define('DB_USER', 'root');
define('DB_PASS', '');


define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');


function db()
{
    static $conn = null;

    if ($conn == null) {
        $conn = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS
        );

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    return $conn;
}


function e($text)
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function flash($key, $message = null)
{
    if ($message != null) {
        $_SESSION['flash'][$key] = $message;
        return;
    }

    if (isset($_SESSION['flash'][$key])) {
        $oldMessage = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $oldMessage;
    }

    return null;
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function current_user_id()
{
    return $_SESSION['user_id'] ?? null;
}

function current_role()
{
    return $_SESSION['role'] ?? null;
}

function require_login()
{
    if (!is_logged_in()) {
        flash('error', 'Please login first.');
        redirect('index.php?controller=auth&action=login');
    }
}

function require_role($role)
{
    require_login();

    if (current_role() != $role) {
        flash('error', 'You are not allowed to access this page.');
        redirect('index.php');
    }
}

function view($file, $data = [])
{
    extract($data);

    include BASE_PATH . '/views/layout/header.php';
    include BASE_PATH . '/views/' . $file . '.php';
    include BASE_PATH . '/views/layout/footer.php';
}

function json_response($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}


function csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function check_csrf()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $token = $_POST['csrf_token'] ?? '';

        if ($token != csrf_token()) {
            die('Invalid form token. Please go back and try again.');
        }
    }
}


function upload_file($file, $folder, $allowedExtensions, $maxSize)
{
    if (!isset($file) || $file['error'] != 0) {
        return [false, 'Please select a file.'];
    }

    if ($file['size'] > $maxSize) {
        return [false, 'File size is too large.'];
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions)) {
        return [false, 'This file type is not allowed.'];
    }

    $newName = time() . '_' . rand(1000, 9999) . '.' . $extension;
    $relativePath = 'uploads/' . $folder . '/' . $newName;
    $targetPath = PUBLIC_PATH . '/' . $relativePath;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return [true, $relativePath];
    }

    return [false, 'File upload failed.'];
}
