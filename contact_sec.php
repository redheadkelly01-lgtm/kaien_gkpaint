<?php
// お問い合わせデータの暗号化・復号化ユーティリティ
define('CONTACT_SEC_KEY', 'kaien_gkpaint_2026_hq_secure_key!');

function encrypt_contacts($data_array) {
    $json = json_encode($data_array, JSON_UNESCAPED_UNICODE);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($json, 'aes-256-cbc', CONTACT_SEC_KEY, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function read_contacts_data($file_path, $old_path = null) {
    $contacts = [];
    if (file_exists($file_path)) {
        $raw = trim(file_get_contents($file_path));
        // 平文JSONもしくはPHP偽装タグ付きの場合はそのままパース
        if (strpos($raw, '[') !== false || strpos($raw, '<?php') !== false) {
            $clean = preg_replace('/^<\?php exit; \?>\s*/', '', $raw);
            $contacts = json_decode($clean, true) ?: [];
        } else {
            // 暗号化データの場合
            $decoded = base64_decode($raw);
            if ($decoded && strpos($decoded, '::') !== false) {
                $parts = explode('::', $decoded, 2);
                $json = openssl_decrypt($parts[0], 'aes-256-cbc', CONTACT_SEC_KEY, 0, $parts[1]);
                $contacts = json_decode($json, true) ?: [];
            }
        }
    } elseif ($old_path && file_exists($old_path)) {
        $raw = file_get_contents($old_path);
        $contacts = json_decode($raw, true) ?: [];
    }
    return is_array($contacts) ? $contacts : [];
}

function save_contacts_data($file_path, $data_array) {
    $content = encrypt_contacts($data_array);
    return file_put_contents($file_path, $content);
}
