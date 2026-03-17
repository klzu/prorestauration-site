<?php
// Webhook de déploiement automatique
// Appelez cette URL depuis GitHub pour déclencher un git pull

$SECRET = 'pr2026deploy'; // Change ce token si tu veux

// Vérification du secret
$token = $_GET['token'] ?? '';
if ($token !== $SECRET) {
    http_response_code(403);
    die('Accès refusé');
}

// Lancement du git pull
$output = shell_exec('cd ' . escapeshellarg(__DIR__) . ' && git fetch origin && git reset --hard origin/main 2>&1');

echo '<pre>' . htmlspecialchars($output) . '</pre>';
echo 'Déployé à ' . date('d/m/Y H:i:s');
