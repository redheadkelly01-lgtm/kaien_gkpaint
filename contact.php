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

$dir  = __DIR__ . '/data';
$file = $dir . '/contacts.json';

// デバッグ情報
$debug = [
    'dir_exists'    => is_dir($dir),
    'dir_writable'  => is_writable($dir),
    'file_exists'   => file_exists($file),
    'file_writable' => file_exists($file) ? is_writable($file) : 'N/A',
    'php_user'      => function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user(),
    'dir_path'      => $dir,
];

if (!is_dir($dir)) {
    if (!mkdir($dir, 0775, true)) {
        echo json_encode(['ok' => false, 'error' => 'dataディレクトリの作成に失敗しました', 'debug' => $debug]);
        exit;
    }
}

if (!is_writable($dir)) {
    echo json_encode(['ok' => false, 'error' => 'dataディレクトリに書き込み権限がありません', 'debug' => $debug]);
    exit;
}

$contacts = [];
if (file_exists($file)) {
    $raw = file_get_contents($file);
    $contacts = json_decode($raw, true) ?: [];
}

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

$result = file_put_contents($file, json_encode($contacts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

if ($result === false) {
    echo json_encode(['ok' => false, 'error' => 'ファイルへの書き込みに失敗しました']);
    exit;
}

echo json_encode(['ok' => true]);
