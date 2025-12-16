<?php
    require_once '../config/database.php';
    require_once '../helpers/response.php';

    $database = new Database();
    $db = $database->getConnection();

    if ($db) {
        Response::success(null, "Database connection successful");
    }
