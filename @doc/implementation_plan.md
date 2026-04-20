# 制作報告（Works）ページへのリンク追加機能の実装計画

制作報告の各記事において、テキストや画像と同様に、好きな位置にリンクボタンを挿入できる仕組みを構築します。

## ユーザーレビューが必要な事項

- **リンクの配置**: 特定のセクションではなく、本文内の「ブロック」の一つとして実装します。これにより、テキスト→画像→リンク→テキストといった自由なレイアウトが可能になります。
- **デザイン**: 制作報告の雰囲気に合わせた、目立ちつつも馴染むボタンデザインを採用します。

## 提案される変更点

### 1. データ構造の定義 [MODIFY] [works.json](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/data/works.json)

`body` 配列内のブロックタイプとして `link` を追加します。

```json
{
  "id": "001",
  ...,
  "body": [
    { "type": "paragraph", "text": "..." },
    { "type": "link", "text": "Amazonで見る", "url": "https://..." },
    { "type": "imageGrid2", "images": [...] }
  ]
}
```

---

### 2. フロントエンド表示対応 [MODIFY] [worksdetail.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/worksdetail.html)

- **CSS**: 本文内に表示されるリンクボタンのスタイリングを追加します。
- **HTML/JS**: `renderBody` 関数を更新し、`link` タイプのブロックをボタンとして描画するようにします。

---

### 3. 管理画面の機能拡張 [MODIFY] [admin/index.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/admin/index.html)

- **UIの実装**: 「本文コンテンツ（ブロック）」エリアに「+ リンク」ボタンを追加します。
- **編集機能**: リンクブロック用のモーダルを作成し、「ボタンのテキスト（ラベル）」と「遷移先URL」を入力できるようにします。
- **ロジック更新**:
    - `renderBodyBlocks`: リンクブロックのプレビュー表示に対応。
    - `openBlockModal` / `saveBlockModal`: リンクブロックの入力・保存に対応。
    - `openWorksPreview`: プレビュー画面での表示に対応。

---

## 検討事項・質問

- [IMPORTANT] リンクの表示スタイルについて、ボタン形式がよろしいでしょうか、それともテキストリンク形式がよろしいでしょうか？（現在はクリックしやすいボタン形式を想定しています）

## 検証計画

### 自動テスト / 動作確認
- [ ] 管理画面でリンクを追加・保存し、`data/works.json` に正しく書き込まれることを確認。
- [ ] 管理画面でリンクの並び替え・削除が正常に動作することを確認。
- [ ] `worksdetail.html`（詳細ページ）で、追加したリンクが正しく表示され、正しく遷移することを確認。
- [ ] プレビュー機能でリンクの表示イメージが確認できることを確認。

### 手動検証
- ブラウザで実際のページを開き、レスポンシブ表示（スマホ・タブレット）でリンクが押しやすいか確認。
