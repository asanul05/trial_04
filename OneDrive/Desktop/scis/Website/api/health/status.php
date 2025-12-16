<?php
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    require_once '../config/database.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    if ($db) {
        Response::success(null, "Database connection successful");
    }
