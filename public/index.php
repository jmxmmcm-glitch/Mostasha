<?php

/**
 * Emergency Medical System
 * Front Controller
 */

// Define absolute path to the root directory
define('ROOT_DIR', dirname(__DIR__));

// Autoloader function for classes
spl_autoload_register(function ($class) {
    // Convert namespace to full file path
    // e.g. App\Core\Router -> app/Core/Router.php
    $prefix = 'App\\';
    $base_dir = ROOT_DIR . '/app/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // move to next registered autoloader
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Application;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\PatientController;
use App\Controllers\VisitController;
use App\Controllers\TriageController;
use App\Controllers\DoctorController;
use App\Controllers\UserController;

$app = new Application();

$app->router->get('/', [HomeController::class, 'index']);
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/patients', [PatientController::class, 'index']);
$app->router->get('/patients/create', [PatientController::class, 'create']);
$app->router->post('/patients/create', [PatientController::class, 'create']);
$app->router->get('/patients/view', [PatientController::class, 'view']);

$app->router->get('/visits', [VisitController::class, 'index']);
$app->router->get('/visits/create', [VisitController::class, 'create']);
$app->router->post('/visits/create', [VisitController::class, 'create']);

$app->router->get('/triage/create', [TriageController::class, 'create']);
$app->router->post('/triage/create', [TriageController::class, 'create']);

$app->router->get('/doctor/create', [DoctorController::class, 'create']);
$app->router->post('/doctor/create', [DoctorController::class, 'create']);

$app->router->get('/users', [UserController::class, 'index']);
$app->router->get('/users/create', [UserController::class, 'create']);
$app->router->post('/users/create', [UserController::class, 'create']);

$app->router->get('/settings', [App\Controllers\SettingController::class, 'index']);
$app->router->post('/settings', [App\Controllers\SettingController::class, 'index']);

$app->router->get('/invoices', [App\Controllers\InvoiceController::class, 'index']);
$app->router->get('/invoices/pay', [App\Controllers\InvoiceController::class, 'pay']);

$app->run();
