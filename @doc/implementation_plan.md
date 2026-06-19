# 特定商取引法に基づく表記ページの追加計画

サイトに新しく「特定商取引法に基づく表記」ページを追加し、スパムや検索避け対策として提供された画像をベースに配置します。また、サイトのフッターにこの新規ページへのリンクを追加します。

## ユーザーレビューが必要な事項

> [!IMPORTANT]
> - **画像ベースの配置**: ユーザー様よりご提示いただいた画像 `images/SpecifiedCommercialTransaction.png` を一枚ペタッと貼り付ける形式で掲載します。
> - **デザインの統一**: サイト全体の高級感・ダークテーマ（背景色 `#1a1a1a`、ゴールドや赤のアクセントカラー）を維持したページを新規作成します。
> - **フッターリンクの追加対象**: 公開されている主要なHTMLファイル（`index.html`、`works.html`、`gallery.html`、`worksdetail.html`）のフッターにリンクを追加します。

## オープン質問

> [!NOTE]
> 特にありません。

## 提案される変更点

### 1. 新規ページの作成

#### [NEW] [tokushoho.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/tokushoho.html)
- サイト全体のヘッダーとフッターを配置した構成。
- メインコンテンツエリアに `images/SpecifiedCommercialTransaction.png` を表示。
- レスポンシブ対応とし、PCなど大きな画面では画像が引き伸ばされすぎないよう `max-width: 800px` の制限をかけて中央寄せにします。

### 2. スタイルシートの変更

#### [MODIFY] [style.css](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/css/style.css)
- `index.html` と `tokushoho.html` が読み込む共通スタイルに、フッターのコピーライト内のリンク用スタイルを追加します。
- `tokushoho.html` のメインコンテンツ用のレイアウトスタイル（セクション余白、画像中央揃えなど）を追加します。

### 3. 各HTMLファイルのフッター変更

以下のファイルのフッターに、「特定商取引法に基づく表記」へのリンクを追加し、スタイル定義も追記します。

#### [MODIFY] [index.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/index.html)
#### [MODIFY] [works.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/works.html)
#### [MODIFY] [gallery.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/gallery.html)
#### [MODIFY] [worksdetail.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/worksdetail.html)

---

## 検証計画

### 手動検証
- [ ] 各ページ（`index.html`、`works.html`、`gallery.html`、`worksdetail.html`）のフッターに「特定商取引法に基づく表記」へのテキストリンクが表示され、正しくホバーエフェクトが効くことを確認。
- [ ] リンクをクリックした際に `tokushoho.html` に正しく遷移することを確認。
- [ ] `tokushoho.html` で特定商取引法に基づく表記の画像（`images/SpecifiedCommercialTransaction.png`）が中央に綺麗に表示されていることを確認。
- [ ] `tokushoho.html` のレスポンシブ表示（スマートフォン表示時）に画像がはみ出さず、適切に縮小されることを確認。
