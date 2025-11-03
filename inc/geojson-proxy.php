<?php
/**
 * Proxy simples para o GeoJSON do mapa
 * - Faz cache do arquivo no servidor para evitar rate-limits do GitHub
 * - Serve o arquivo cacheado para o frontend
 * Coloque este arquivo em: inc/geojson-proxy.php e aponte a URL no JS para /wp-content/themes/<tema>/inc/geojson-proxy.php
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$remote = 'https://raw.githubusercontent.com/codeforamerica/click_that_hood/master/public/data/brazil-states.geojson';
$cacheDir = __DIR__ . '/cache';
$cacheFile = $cacheDir . '/brazil-states.geojson';
$cacheTtl = 24 * 60 * 60; // 24 horas

if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0755, true);
}

// Serve cache se existir e não estiver expirado
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
    readfile($cacheFile);
    exit;
}

// Caso contrário, busca do remoto
$opts = [
    'http' => [
        'method' => 'GET',
        'timeout' => 10,
        'header' => "User-Agent: PHP GeoJSON Proxy\r\nAccept: application/json\r\n"
    ]
];

$context = stream_context_create($opts);
$content = @file_get_contents($remote, false, $context);

if ($content === false) {
    // Se falhou, tenta servir cache mesmo que expirado
    if (file_exists($cacheFile)) {
        readfile($cacheFile);
        exit;
    }
    http_response_code(502);
    echo json_encode(['error' => 'Não foi possível obter GeoJSON remoto']);
    exit;
}

// Salva no cache e serve
@file_put_contents($cacheFile, $content);
echo $content;
exit;
