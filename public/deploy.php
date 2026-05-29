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

// ── Dézipper vendor.zip si présent (envoyé par GitHub Actions) ────────────
$vendorZip = "{$base}/vendor.zip";
if (($_SERVER['HTTP_X_VENDOR_UPDATED'] ?? 'false') === 'true' && file_exists($vendorZip)) {
    $zip = new ZipArchive();
    if ($zip->open($vendorZip) === true) {
        $zip->extractTo($base);
        $zip->close();
        unlink($vendorZip);
        $log[] = ['step' => 'vendor_extract', 'status' => 'ok', 'msg' => 'vendor.zip extrait et supprimé'];
    } else {
        $log[] = ['step' => 'vendor_extract', 'status' => 'error', 'msg' => 'Impossible d\'ouvrir vendor.zip'];
    }
}

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
