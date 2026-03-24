# ConoHa VPS 初期セットアップガイド (Rocky Linux / RHEL系)

現在のサーバー環境 (Rocky Linux 等) に合わせたセットアップ手順です。

## 1. ConoHaコントロールパネルでの設定
(前述と同じため省略：SSH 22 / Web 80,443 を許可)

---

## 2. 初回のSSH接続
(前述と同じ：ssh root@133.88.116.248)

---

## 3. Webサーバーの構築 (Apache + PHP)

Rocky Linux では `dnf` を使用します。

```bash
# WebサーバーとPHPのインストール
dnf install -y httpd php php-json git

# Webサーバーの起動と自動起動設定
systemctl start httpd
systemctl enable httpd

# OS側のファイアウォール設定 (HTTP/HTTPS許可)
firewall-cmd --permanent --add-service=http
firewall-cmd --permanent --add-service=https
firewall-cmd --reload
```

---

## 4. サイト用ディレクトリの準備

```bash
mkdir -p /var/www/html/kaien
chown -R apache:apache /var/www/html/kaien
```

---

## 5. ApacheのVirtualHost設定

ドメイン (`kaien-gkpaint.com`) でサイトを表示するための設定です。

1. 設定ファイルを作成：
   `nano /etc/httpd/conf.d/kaien.conf`

2. 以下の内容を貼り付け：
```apache
<VirtualHost *:80>
    ServerName kaien-gkpaint.com
    ServerAlias www.kaien-gkpaint.com
    DocumentRoot /var/www/html/kaien

    <Directory /var/www/html/kaien>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog /var/log/httpd/kaien_error.log
    CustomLog /var/log/httpd/kaien_access.log combined
</VirtualHost>
```

3. 設定を反映：
```bash
systemctl restart httpd
```


---

これで、ドメインが浸透すればブラウザからサイトが見られるようになります！
準備ができたら、先ほどの **Gitデプロイ設定**（bareリポジトリ作成）に進んでください。
