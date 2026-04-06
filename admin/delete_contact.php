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

$content = "<?php exit; ?>\n" . json_encode($newContacts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
$result = file_put_contents($file, $content);
if ($result !== false && file_exists($oldFile)) {
    @unlink($oldFile);
}

echo json_encode(['ok' => $result !== false]);
