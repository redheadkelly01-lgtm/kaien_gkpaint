# 基本料金訂正計画

料金表に記載されている基本料金の価格を、現在の「60,000円 〜 80,000円」から「80,000円 〜 100,000円」へ訂正します。

## ユーザーレビューが必要な事項

> [!IMPORTANT]
> - **対象ファイル**: テキストベースの料金表がある `index.html` のみを変更します。
> - **内容**:
>   - 基本料金の部分を「80,000円 〜 100,000円」に変更します。
>   - なお、`index2.html` はすでに「80,000円 〜 100,000円」となっており、`index3.html` は画像（ryoukin.webp）による表示のため、変更対象は `index.html` のみとなります。

## 提案される変更点

### 1. index.html の基本料金の更新

#### [MODIFY] [index.html](file:///c:/Users/kmurata/Documents/order/order/gumplabot/gunplasaihan/kaien2/index.html)
- 241行目付近

**変更前:**
```html
                <div class="basic-fee-amount">60,000<span>円 〜</span>80,000<span>円</span></div>
```

**変更後:**
```html
                <div class="basic-fee-amount">80,000<span>円 〜</span>100,000<span>円</span></div>
```

---

## 検証計画

### 手動検証
- [ ] `index.html` をブラウザで開き、料金表セクション（基本料金）の表示が「80,000円 〜 100,000円」に正しく更新されていることを確認。
