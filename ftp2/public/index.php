<?php

require_once __DIR__ . '/../config/app.php';

require_once BASE_PATH . '/models/userModel.php';
require_once BASE_PATH . '/models/categoryModel.php';
require_once BASE_PATH . '/models/contentModel.php';
require_once BASE_PATH . '/models/requestModel.php';

require_once BASE_PATH . '/controllers/homeController.php';
require_once BASE_PATH . '/controllers/authController.php';
require_once BASE_PATH . '/controllers/adminController.php';
require_once BASE_PATH . '/controllers/moderatorController.php';

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

$functionName = $controller . '_' . $action;

if (function_exists($functionName)) {
    $functionName();
} else {
    http_response_code(404);
    echo 'Page not found.';
}
