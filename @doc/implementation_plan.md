# 問い合わせフォーム内容変更計画

問い合わせフォーム上部に表示されている「最短着手時期」のアナウンス文言を、現在の最新スケジュールを参照するように促す内容に更新します。

## ユーザーレビューが必要な事項

> [!IMPORTANT]
> - **対象ファイル**: 問い合わせフォームが設置されている `index.html`, `index2.html`, `index3.html` の3ファイルすべてに対して同様に変更を適用します。
> - **変更内容**:
>   - 警告ボックスのタイトルから「⚠️」アイコンおよび具体的な時期（2026年11月〜）を削除し、「現在の最短着手時期について」に変更します。
>   - 本文を、インフォメーション欄の「現在のスケジュールについて」を確認するように促す文章に変更します。

## 提案される変更点

### 1. フォーム内のアナウンス内容の更新

以下の3つのHTMLファイルの `#contactForm` 内にある警告ブロック（`.contact-notice`）のテキストを更新します。

#### [MODIFY] [index.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/index.html)
- 378〜383行目付近

#### [MODIFY] [index2.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/index2.html)
- 374〜379行目付近

#### [MODIFY] [index3.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/index3.html)
- 1158〜1163行目付近

#### 変更内容詳細（全ファイル共通）

**変更前:**
```html
        <div class="contact-notice">
          <div class="contact-notice-title">⚠️ 現在の最短着手時期：2026年11月〜</div>
          <p class="contact-notice-body">
            現在、数ヶ月先までスケジュールが埋まっておりますため、今からのお申し込みは「11月以降の着手」となります。ご了承の上、下記フォームよりお見積もりをご依頼ください。
          </p>
        </div>
```

**変更后:**
```html
        <div class="contact-notice">
          <div class="contact-notice-title">現在の最短着手時期について</div>
          <p class="contact-notice-body">
            現在、スケジュールが埋まっておりますため、informationの「現在のスケジュールについて」をご覧のうえ、下記フォームよりお見積もりをご依頼ください。
          </p>
        </div>
```

---

## 検証計画

### 手動検証
- [ ] 各HTML（`index.html`, `index2.html`, `index3.html`）をローカル環境で開き、CONTACTセクションの案内文言が指定のものに正しく書き換わっていることを確認。
- [ ] スタイル崩れなどが起きていないことを確認。
