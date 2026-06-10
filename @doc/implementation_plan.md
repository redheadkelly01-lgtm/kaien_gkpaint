# 問い合わせフォームへの最短着手時期アナウンスの追加計画

CONTACTページ（問い合わせフォーム）の入力欄のすぐ上に、最短着手時期（2026年11月〜）を伝える注意喚起のアナウンスを目立つように追加します。

## ユーザーレビューが必要な事項

> [!IMPORTANT]
> - **対象ファイル**: 現在フォームが設置されている `index.html`, `index2.html`, `index3.html` の3ファイルすべてに対して同様にアナウンスを追加します。
> - **表示場所**: 問い合わせフォーム（`#contactForm`）の開始タグ直下（「お名前」入力欄のすぐ上）に挿入します。
> - **デザイン**: サイト全体のダーク系デザインと調和させつつ、ユーザーの目を引くために、枠線（ボーダー）と背景色を定義した警告ボックス形式（アラートボックス）で表示します。

## 提案される変更点

### 1. スタイルの定義 [MODIFY] [style.css](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/css/style.css)

警告メッセージをスタイリッシュかつ目立つように表示するためのCSSを追加します。

```css
/* CONTACT NOTICE */
.contact-notice {
  background-color: rgba(192, 57, 43, 0.1); /* サイトのアクセントカラー赤の透明度10% */
  border: 1px solid var(--accent); /* 赤いボーダー */
  padding: 20px;
  margin-bottom: 24px;
  border-radius: 4px;
  text-align: left;
}

.contact-notice-title {
  color: var(--accent2); /* 明るい赤色 */
  font-weight: bold;
  margin-bottom: 10px;
  font-size: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.contact-notice-body {
  font-size: 14px;
  color: var(--text);
  line-height: 1.7;
  margin: 0;
}
```

---

### 2. フォームへのアナウンス追加

以下の3つのHTMLファイルの `#contactForm` 開始直後にアナウンス要素を挿入します。

#### [MODIFY] [index.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/index.html)
#### [MODIFY] [index2.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/index2.html)
#### [MODIFY] [index3.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/index3.html)

追加するHTMLコード例：
```html
      <form id="contactForm">
        <!-- 追加部分 -->
        <div class="contact-notice">
          <div class="contact-notice-title">⚠️ 現在の最短着手時期：2026年11月〜</div>
          <p class="contact-notice-body">
            現在、数ヶ月先までスケジュールが埋まっておりますため、今からのお申し込みは「11月以降の着手」となります。ご了承の上、下記フォームよりお見積もりをご依頼ください。
          </p>
        </div>
        
        <div class="form-group">
          <label class="form-label">お名前</label>
...
```

---

## 検証計画

### 手動検証
- [ ] 各HTML（`index.html`, `index2.html`, `index3.html`）をブラウザで表示し、CONTACTセクションの問い合わせフォーム内にアナウンスが正しく表示されることを確認。
- [ ] スマートフォン表示（レスポンシブ）を含む各種画面幅で、警告ボックスのレイアウトが崩れず、正しくフォントサイズや余白が適用されていることを確認。
