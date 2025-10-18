<?php
$cfg = parse_ini_file(__DIR__ . '/../.env', true);
// fallback parse .env manually for DB_* values
$env = file_get_contents(__DIR__ . '/../.env');
preg_match('/DB_HOST=(.*)/', $env, $m); $host = trim($m[1] ?? '127.0.0.1');
preg_match('/DB_PORT=(.*)/', $env, $m); $port = trim($m[1] ?? '3306');
preg_match('/DB_DATABASE=(.*)/', $env, $m); $db = trim($m[1] ?? 'myproject');
preg_match('/DB_USERNAME=(.*)/', $env, $m); $user = trim($m[1] ?? 'root');
preg_match('/DB_PASSWORD=(.*)/', $env, $m); $pass = trim($m[1] ?? '');

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query("SELECT id, profile_id, prospect_id, prospect_name, shortlisted_by, user_id, source, created_at FROM shortlists ORDER BY created_at DESC LIMIT 50");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
