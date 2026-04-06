<?php
// .htaccessでアクセス拒否されているcontacts.jsonを、Basic認証済みのadmin側から読み込むための中継API
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../contact_sec.php';

$file = __DIR__ . '/../data/contacts.php';
$oldFile = __DIR__ . '/../data/contacts.json';

$contacts = read_contacts_data($file, $oldFile);
echo json_encode($contacts, JSON_UNESCAPED_UNICODE);

