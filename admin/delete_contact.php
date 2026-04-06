<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
    exit;
}

require_once __DIR__ . '/../contact_sec.php';

$id   = trim($_POST['id'] ?? '');
$file = __DIR__ . '/../data/contacts.php';
$oldFile = __DIR__ . '/../data/contacts.json';

if (empty($id)) {
    echo json_encode(['ok' => false, 'error' => 'IDが指定されていません']);
    exit;
}

$contacts = read_contacts_data($file, $oldFile);
if (empty($contacts) && !file_exists($file) && !file_exists($oldFile)) {
    echo json_encode(['ok' => false, 'error' => 'contacts.phpが見つかりません']);
    exit;
}
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

$result = save_contacts_data($file, $newContacts);
if ($result !== false && file_exists($oldFile)) {
    @unlink($oldFile);
}

echo json_encode(['ok' => $result !== false]);
