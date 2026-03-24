# SSL自動更新設定ガイド（Let's Encrypt + Certbot）

> ⚠️ **ドメイン取得後に実施してください。** IPアドレスではSSL証明書は発行できません。  
> ドメイン取得後、このガイドを参照してエンジニアに設定を依頼してください。

---

## 概要

Let's EncryptのSSL証明書は**90日で失効**します。  
Certbot + cronを設定することで、**証明書の更新を自動化**できます。

---

## Step 1: Certbotのインストール

```bash
# SSHでVPSにログイン
ssh root@133.88.116.248

# snapdでCertbotをインストール（Ubuntu/Debian系）
apt update
apt install -y snapd
snap install --classic certbot
ln -s /snap/bin/certbot /usr/bin/certbot
```

---

## Step 2: SSL証明書の初回発行

```bash
# Apacheの場合
certbot --apache -d example.com -d www.example.com

# Nginxの場合
certbot --nginx -d example.com -d www.example.com
```

`example.com` はご自身のドメインに置換してください。

---

## Step 3: 自動更新の設定

Certbotインストール後、以下のコマンドでcronが登録されているか確認：

```bash
systemctl status snap.certbot.renew.timer
```

手動でcronを追加する場合：

```bash
crontab -e
```

以下の行を追加（月2回、午前3時に自動更新を試みる）：

```
0 3 1,15 * * certbot renew --quiet
```

---

## Step 4: 動作確認

```bash
# 更新テスト（実際には更新しないドライラン）
certbot renew --dry-run
```

エラーが出なければ設定完了です。

---

## 注意事項

- ポート80（HTTP）がファイアウォールで開放されている必要があります
- 証明書の有効期限は `certbot certificates` で確認できます
- Let's Encryptの制限: 1ドメインあたり週5回まで発行可能
