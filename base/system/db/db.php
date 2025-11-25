<?php
// db.php

function get_db_connection() {
    $host = "localhost";
    $db   = "your_database";
    $user = "your_username";
    $pass = "your_password";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;

    } catch (PDOException $e) {
        die(json_encode(["error" => "Database connection failed: " . $e->getMessage()]));
    }
}

function get_contacts() {
    $pdo = get_db_connection();

    $stmt = $pdo->prepare("SELECT id, name, email, phone FROM contacts ORDER BY name ASC");
    $stmt->execute();

    return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
