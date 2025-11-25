<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

// Database Connection + get_contacts()
require "../system/db/db.php";

// Check if user has permission
if (!has_permission('view_contacts')) {
    echo json_encode(['error' => 'You do not have permission to view contacts']);
    exit();
}

// Fetch contacts
$contacts = get_contacts();
$contacts = json_decode($contacts, true);

// Validate result
if (empty($contacts)) {
    echo json_encode(['error' => 'No contacts found']);
    exit();
}

// Return contacts
echo json_encode($contacts);
