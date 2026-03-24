# Google Search Console 登録手順ガイド

サイトをGoogle検索結果に表示（インデックス登録）させるための手順です。

## 1. Google Search Console にログイン
[Google Search Console](https://search.google.com/search-console/about) にアクセスし、Googleアカウントでログインします。

## 2. プロパティの追加
1. 左上のプロパティ選択メニューから「プロパティを追加」をクリックします。
2. 「URL プレフィックス」を選択し、以下のURLを入力します。
   `https://kaien-gkpaint.com/`

## 3. 所有権の確認
複数の方法がありますが、以下のいずれかを推奨します。

### A. HTML ファイルのアップロード（推奨）
1. Search Consoleから提供される `googlexxxxxxxxxxxx.html` というファイルをダウンロードします。
2. そのファイルをサーバーの公開ディレクトリ（`index.html` と同じ場所）にアップロードします。
3. Search Consoleで「確認」ボタンをクリックします。

### B. HTML タグ
1. 提供される `<meta name="google-site-verification" content="..." />` というタグをコピーします。
2. `index.html` の `<head>` セクション内に追加します。
3. Search Consoleで「確認」ボタンをクリックします。

## 4. サイトマップの送信
所有権の確認ができたら、Googleにサイト全体の構成を伝えます。
1. 左メニューの「サイトマップ」をクリックします。
2. 「新しいサイトマップの追加」に `sitemap.xml` と入力して「送信」をクリックします。

---
> [!TIP]
> 登録後、データが反映されるまで数日から1週間程度かかる場合があります。
