# Gitデプロイ運用ガイド

> VPS情報: `133.88.116.248`  
> ドメイン確定後はIPアドレスをドメインに置換してください。

---

## 概要

ローカルで `git push` するだけで、VPSへ自動デプロイされる仕組みを構築します。

```
ローカル開発
    ↓ git push production main
VPS上のbareリポジトリ（kaien.git）
    ↓ post-receive フック自動実行
公開ディレクトリ（/var/www/html/kaien）に反映
```

---

## Step 1: VPS側の初期設定（エンジニアが1回だけ実施）

### 1-1. SSHでVPSにログイン

```bash
ssh root@133.88.116.248
```

### 1-2. 公開ディレクトリを作成

```bash
mkdir -p /var/www/html/kaien
```

### 1-3. bareリポジトリを作成

```bash
mkdir -p /var/repos/kaien.git
cd /var/repos/kaien.git
git init --bare
```

### 1-4. post-receiveフックを作成

```bash
nano /var/repos/kaien.git/hooks/post-receive
```

以下の内容を貼り付けて保存（Ctrl+X → Y → Enter）:

```bash
#!/bin/bash
GIT_WORK_TREE=/var/www/html/kaien GIT_DIR=/var/repos/kaien.git git checkout -f main
echo "=== Deploy complete: $(date) ==="
```

### 1-5. フックに実行権限を付与

```bash
chmod +x /var/repos/kaien.git/hooks/post-receive
```

---

## Step 2: ローカルの初期設定（1回だけ実施）

PowerShellでプロジェクトフォルダに移動して実行：

```powershell
cd c:\Users\kmurata\Documents\order\order\gumplabot\gunplasaihan\kaien2

# Gitリポジトリを初期化
git init

# リモートを登録（production = VPS）
git remote add production ssh://root@133.88.116.248/var/repos/kaien.git

# 初回コミット
git add -A
git commit -m "initial commit: KAIEN GK PAINTING SERVICE"

# VPSへ初回デプロイ
git push production main
```

> ⚠️ `git push` 時にVPSのパスワードまたはSSH鍵が求められます。  
> SSH鍵認証を設定しておくと、毎回パスワード入力が不要になります。

---

## Step 3: 日常のデプロイ手順（毎回の更新時）

```powershell
# ファイルを編集後
git add -A
git commit -m "update: 更新内容を一言で"
git push production main
```

これだけでVPSに自動反映されます。

---

## SSH鍵認証の設定（推奨・パスワード入力を省略）

```powershell
# ローカルでSSH鍵を生成（すでにある場合はスキップ）
ssh-keygen -t ed25519 -C "kaien-deploy"

# 公開鍵をVPSに登録
ssh-copy-id root@133.88.116.248
```

---

## ドメイン取得後の変更箇所

以下のファイルのIPアドレスをドメインに一括置換してください：

| ファイル | 変更箇所 |
|---------|---------|
| `index.html` | OGPタグ・構造化データのURL |
| `gallery.html` | OGPタグのURL |
| `works.html` | OGPタグのURL |
| `works-detail.html` | OGPタグのURL |
| `sitemap.xml` | 全URLの `loc` |
| `robots.txt` | `Sitemap:` のURL |
| `@doc/git-deploy.md` | リモートURL |
| `@doc/vps-backup.md` | 参考URL |
