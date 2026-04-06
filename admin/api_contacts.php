<?php
// .htaccessでアクセス拒否されているcontacts.jsonを、Basic認証済みのadmin側から読み込むための中継API
header('Content-Type: application/json; charset=utf-8');

$file = __DIR__ . '/../data/contacts.json';
if (file_exists($file)) {
    readfile($file);
} else {
    echo '[]';
}
