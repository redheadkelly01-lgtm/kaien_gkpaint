<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// 許可する拡張子・MIMEタイプ
$allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$allowed_exts  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

// アップロード先ディレクトリ
$upload_dir = __DIR__ . '/images/works/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0775, true);
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $codes = [
        UPLOAD_ERR_INI_SIZE   => 'ファイルサイズがサーバー上限を超えています',
        UPLOAD_ERR_FORM_SIZE  => 'ファイルサイズが上限を超えています',
        UPLOAD_ERR_PARTIAL    => 'ファイルが一部しかアップロードされませんでした',
        UPLOAD_ERR_NO_FILE    => 'ファイルが選択されていません',
        UPLOAD_ERR_NO_TMP_DIR => '一時フォルダが見つかりません',
        UPLOAD_ERR_CANT_WRITE => 'ファイルの書き込みに失敗しました',
    ];
    $code = $_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE;
    echo json_encode(['ok' => false, 'error' => $codes[$code] ?? 'アップロードエラー']);
    exit;
}

$file     = $_FILES['file'];
$mime     = mime_content_type($file['tmp_name']);
$ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$filename = isset($_POST['filename']) && trim($_POST['filename']) !== ''
    ? preg_replace('/[^a-zA-Z0-9_\-]/', '', trim($_POST['filename'])) . '.' . $ext
    : basename($file['name']);

// バリデーション
if (!in_array($mime, $allowed_types)) {
    echo json_encode(['ok' => false, 'error' => '許可されていないファイル形式です（JPG/PNG/WebP/GIFのみ）']);
    exit;
}
if (!in_array($ext, $allowed_exts)) {
    echo json_encode(['ok' => false, 'error' => '許可されていない拡張子です']);
    exit;
}
if ($file['size'] > 10 * 1024 * 1024) {
    echo json_encode(['ok' => false, 'error' => 'ファイルサイズは10MB以内にしてください']);
    exit;
}

$dest = $upload_dir . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    $err = error_get_last();
    $debug = [
        'upload_dir_exists'   => is_dir($upload_dir),
        'upload_dir_writable' => is_writable($upload_dir),
        'tmp_file_exists'     => file_exists($file['tmp_name']),
        'tmp_file_readable'   => is_readable($file['tmp_name']),
        'php_user'            => get_current_user(),
        'last_error'          => $err ? $err['message'] : 'none',
    ];
    echo json_encode([
        'ok'    => false,
        'error' => 'ファイルの保存に失敗しました。images/works/ の書き込み権限を確認してください。',
        'debug' => $debug
    ]);
    exit;
}

echo json_encode([
    'ok'   => true,
    'path' => 'images/works/' . $filename,
    'name' => $filename,
]);
