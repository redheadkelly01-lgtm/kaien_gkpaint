<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
    exit;
}

$id   = trim($_POST['id'] ?? '');
$file = __DIR__ . '/data/contacts.json';

if (empty($id)) {
    echo json_encode(['ok' => false, 'error' => 'IDが指定されていません']);
    exit;
}

if (!file_exists($file)) {
    echo json_encode(['ok' => false, 'error' => 'contacts.jsonが見つかりません']);
    exit;
}

$contacts = json_decode(file_get_contents($file), true) ?: [];
$beforeCount = count($contacts);

// 指定されたID以外のデータを残す
$newContacts = [];
foreach ($contacts as $c) {
    if ($c['id'] !== $id) {
        $newContacts[] = $c;
    }
}

if (count($newContacts) === $beforeCount) {
    echo json_encode(['ok' => false, 'error' => '該当IDが見つかりません']);
    exit;
}

$result = file_put_contents($file, json_encode($newContacts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo json_encode(['ok' => $result !== false]);
