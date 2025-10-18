<?php
// Simple inspector for shortlists columns
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
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $stmt = $pdo->prepare("SELECT COLUMN_NAME, DATA_TYPE, COLUMN_TYPE, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :schema AND TABLE_NAME = 'shortlists'");
    $stmt->execute(['schema' => $db]);
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "COLUMNS:\n";
    foreach ($cols as $c) {
        echo " - {$c['COLUMN_NAME']} ({$c['COLUMN_TYPE']}) nullable={$c['IS_NULLABLE']}\n";
    }
    // Show some sample rows
    $rows = $pdo->query("SELECT * FROM shortlists LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    echo "\nSAMPLE ROWS:\n";
    foreach ($rows as $r) {
        echo json_encode($r) . "\n";
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
