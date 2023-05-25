<?php
ini_set('display_errors', '1');
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use MyApp\controllers\Controller;

$controller = new Controller();

$action = $_GET['action'] ?? 'index';
match ($action) {
    'create' => $controller->createAction(),
    'login' => $controller->loginAction(),
    'logout' => $controller->logoutAction(),
    'edit' => $controller->editAction(),
    'update' => $controller->updateAction(),
    'delete' => $controller->deleteAction(),
    default => $controller->indexAction(),
};
