# 自動バックアップ設定ガイド

> VPS情報: `133.88.116.248`

---

## 概要

毎日深夜4時に、サイトのファイルを自動バックアップします。  
7世代（7日分）を保持し、古いものは自動削除されます。

---

## 対象ファイル

| ディレクトリ | 内容 |
|------------|------|
| `/var/www/html/kaien/` | HTMLファイル、PHP、JSON、画像など全て |

---

## Step 1: バックアップスクリプトを作成

```bash
# SSHでVPSにログイン
ssh root@133.88.116.248

# バックアップ保存先ディレクトリを作成
mkdir -p /backup/kaien

# スクリプトを作成
nano /usr/local/bin/kaien-backup.sh
```

以下の内容を貼り付けて保存（Ctrl+X → Y → Enter）:

```bash
#!/bin/bash
DATE=$(date +%Y%m%d)
BACKUP_DIR="/backup/kaien"
SRC="/var/www/html/kaien"
FILE="${BACKUP_DIR}/kaien_${DATE}.tar.gz"

# アーカイブ作成
tar -czf "$FILE" "$SRC"

# 7世代より古いファイルを削除
find "$BACKUP_DIR" -name "kaien_*.tar.gz" -mtime +7 -delete

echo "Backup completed: $FILE"
```

---

## Step 2: 実行権限を付与

```bash
chmod +x /usr/local/bin/kaien-backup.sh
```

---

## Step 3: 動作確認（手動実行）

```bash
/usr/local/bin/kaien-backup.sh

# バックアップが作成されていることを確認
ls -lh /backup/kaien/
```

---

## Step 4: cronで自動実行を設定

```bash
crontab -e
```

以下の行を追加（毎日午前4時に実行）：

```
0 4 * * * /usr/local/bin/kaien-backup.sh >> /var/log/kaien-backup.log 2>&1
```

---

## バックアップからの復元方法

```bash
# バックアップファイルの一覧を確認
ls /backup/kaien/

# 特定の日付のバックアップを復元
tar -xzf /backup/kaien/kaien_20260324.tar.gz -C /

# ※ 上記コマンドは /var/www/html/kaien/ に上書き展開されます
```

---

## ログの確認

```bash
tail -20 /var/log/kaien-backup.log
```
