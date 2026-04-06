<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
    exit;
}

$id   = trim($_POST['id'] ?? '');
$file = __DIR__ . '/../data/contacts.php';
$oldFile = __DIR__ . '/../data/contacts.json';

if (empty($id)) {
    echo json_encode(['ok' => false, 'error' => 'IDが指定されていません']);
    exit;
}

if (file_exists($file)) {
    $raw = file_get_contents($file);
    $raw = preg_replace('/^<\?php exit; \?>\s*/', '', $raw);
    $contacts = json_decode($raw, true) ?: [];
} elseif (file_exists($oldFile)) {
    $raw = file_get_contents($oldFile);
    $contacts = json_decode($raw, true) ?: [];
} else {
    echo json_encode(['ok' => false, 'error' => 'contacts.phpが見つかりません']);
    exit;
}
$updated  = false;

foreach ($contacts as &$c) {
    if ($c['id'] === $id) {
        $c['read'] = true;
        $updated   = true;
        break;
    }
}
unset($c);

if (!$updated) {
    echo json_encode(['ok' => false, 'error' => '該当IDが見つかりません']);
    exit;
}

$result = file_put_contents($file, json_encode($contacts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo json_encode(['ok' => $result !== false]);
