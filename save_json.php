<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
    exit;
}

$type = $_POST['type'] ?? '';
$json_data = $_POST['data'] ?? '';

if (!in_array($type, ['works', 'gallery'])) {
    echo json_encode(['ok' => false, 'error' => '不正なデータタイプです']);
    exit;
}

$file = __DIR__ . '/data/' . $type . '.json';
$decoded = json_decode($json_data);

if ($decoded === null) {
    echo json_encode(['ok' => false, 'error' => 'JSONフォーマットが不正です']);
    exit;
}

$result = file_put_contents($file, json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

if ($result === false) {
    echo json_encode([
        'ok' => false, 
        'error' => 'ファイルの保存に失敗しました。data/ フォルダおよび data/*.json ファイルの書き込み権限（例: chmod 666 または 777）をご確認ください。',
        'debug' => error_get_last()
    ]);
    exit;
}

echo json_encode(['ok' => true]);
