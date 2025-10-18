<?php
require __DIR__ . '/../vendor/autoload.php';
// Read .env
$dotenv = __DIR__ . '/../.env';
$env = [];
if (file_exists($dotenv)) {
    $lines = file($dotenv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (!strpos($line, '=')) continue;
        list($k,$v) = explode('=', $line, 2);
        $env[trim($k)] = trim($v, " \"'");
    }
}
$host = $env['DB_HOST'] ?? '127.0.0.1';
$db = $env['DB_DATABASE'] ?? 'myproject';
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $now = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO shortlists (profile_id, prospect_id, source, prospect_name, prospect_contact, shortlisted_by, status, created_at, updated_at, user_id) VALUES (?, ?, ?, ?, ?, ?, 'new', ?, ?, ?)");
    $profileId = 'AUTO-TEST-' . time();
    $prospectId = 'PTEST123';
    $source = 'others';
    $prospectName = 'Test Prospect';
    $prospectContact = '9999999999';
    $shortlistedBy = 23185; // sample staff id from logs
    $userId = 99999; // legacy user_id field exists and is NOT NULL; set to 99999 to satisfy NOT NULL
    $stmt->execute([$profileId, $prospectId, $source, $prospectName, $prospectContact, $shortlistedBy, $now, $now, $userId]);
    echo "Inserted shortlist row id: " . $pdo->lastInsertId() . "\n";
    $res = $pdo->query("SELECT * FROM shortlists WHERE id = " . $pdo->lastInsertId())->fetch(PDO::FETCH_ASSOC);
    echo json_encode($res, JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
