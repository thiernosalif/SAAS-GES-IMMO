<?php
/**
 * Webhook de déploiement — appelé par GitHub Actions après upload FTP.
 * Protégé par un token secret (X-Deploy-Token header).
 */

// Sécurité : vérification du token
$token = $_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? '';
$secret = getenv('DEPLOY_SECRET') ?: '';

if (!$secret || !hash_equals($secret, $token)) {
    http_response_code(403);
    exit(json_encode(['error' => 'Unauthorized']));
}

$base = dirname(__DIR__);
$php  = PHP_BINARY;
$log  = [];

$commands = [
    "{$php} {$base}/artisan migrate --force --no-interaction",
    "{$php} {$base}/artisan optimize:clear",
    "{$php} {$base}/artisan optimize",
    "{$php} {$base}/artisan storage:link 2>/dev/null || true",
];

foreach ($commands as $cmd) {
    exec($cmd . ' 2>&1', $output, $code);
    $log[] = ['cmd' => $cmd, 'output' => implode("\n", $output), 'code' => $code];
    $output = [];
}

// Écrire le log
$logFile = "{$base}/storage/logs/deploy.log";
$entry   = date('Y-m-d H:i:s') . " — Deploy OK\n" .
           json_encode($log, JSON_PRETTY_PRINT) . "\n" .
           str_repeat('-', 60) . "\n";
@file_put_contents($logFile, $entry, FILE_APPEND);

header('Content-Type: application/json');
echo json_encode(['status' => 'ok', 'deployed_at' => date('Y-m-d H:i:s'), 'log' => $log]);
