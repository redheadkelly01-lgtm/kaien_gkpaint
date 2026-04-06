<?php
header('Content-Type: application/json; charset=utf-8');
// 同一ドメイン内からのアクセスのみを許可（セキュリティ強化）
// header('Access-Control-Allow-Origin: *'); 

ini_set('display_errors', 0);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Tokyo');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
    exit;
}

$emailRaw = trim($_POST['reply_to'] ?? '');
if (!empty($emailRaw) && !filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => '有効なメールアドレスを入力してください']);
    exit;
}

$entry = [
    'id'         => uniqid(),
    'date'       => date('Y-m-d H:i:s'),
    'name'       => htmlspecialchars(trim($_POST['from_name']   ?? ''), ENT_QUOTES, 'UTF-8'),
    'email'      => htmlspecialchars($emailRaw, ENT_QUOTES, 'UTF-8'),
    'kit_name'   => htmlspecialchars(trim($_POST['kit_name']    ?? ''), ENT_QUOTES, 'UTF-8'),
    'maker_name' => htmlspecialchars(trim($_POST['maker_name']  ?? ''), ENT_QUOTES, 'UTF-8'),
    'finish'     => htmlspecialchars(trim($_POST['finish']      ?? ''), ENT_QUOTES, 'UTF-8'),
    'message'    => htmlspecialchars(trim($_POST['message']     ?? ''), ENT_QUOTES, 'UTF-8'),
    'image_url'  => htmlspecialchars(trim($_POST['image_url']   ?? ''), ENT_QUOTES, 'UTF-8'),
    'ip'         => $_SERVER['REMOTE_ADDR'],
    'read'       => false,
];

// ハニーポット（ボット対策）: 非表示フィールドに値が入っていたらボットとみなす
if (!empty($_POST['contact_me_by_fax_only'])) {
    echo json_encode(['ok' => true]); // 見かけ上は成功させる
    exit;
}

if (empty($entry['name']) || empty($entry['email'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'お名前とメールアドレスは必須です']);
    exit;
}

require_once __DIR__ . '/contact_sec.php';

$dir  = __DIR__ . '/data';
$file = $dir . '/contacts.php';
$oldFile = $dir . '/contacts.json';

$contacts = read_contacts_data($file, $oldFile);

// ボット/スパム対策（レートリミット）
$ip = $_SERVER['REMOTE_ADDR'];
$timeLimit = 60; // 60秒以内の連続送信をブロック
foreach ($contacts as $c) {
    if (isset($c['ip']) && $c['ip'] === $ip) {
        $lastTime = strtotime($c['date']);
        if (time() - $lastTime < $timeLimit) {
            http_response_code(429);
            echo json_encode(['ok' => false, 'error' => '連続送信はできません。しばらく時間をおいてから再度お試しください。']);
            exit;
        }
        break; // 直近の履歴だけで判定
    }
}

array_unshift($contacts, $entry);
$contacts = array_slice($contacts, 0, 300);

$result = save_contacts_data($file, $contacts);

if ($result !== false && file_exists($oldFile)) {
    @unlink($oldFile); // 古いファイルを削除
}

if ($result === false) {
    echo json_encode(['ok' => false, 'error' => 'ファイルへの書き込みに失敗しました']);
    exit;
}

echo json_encode(['ok' => true]);

