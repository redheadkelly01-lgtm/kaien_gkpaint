// index2.html 用の拡縮ロジック
function scaleIntro() {
  const container = document.querySelector('.intro-container');
  const scaler = document.querySelector('.intro-scaler');
  if (!container || !scaler) return;
  
  const windowWidth = document.documentElement.clientWidth;
  if (windowWidth < 960) {
    const scale = windowWidth / 960;
    scaler.style.transformOrigin = 'top left';
    scaler.style.margin = '0';
    scaler.style.transform = `scale(${scale})`;
    container.style.height = `${scaler.offsetHeight * scale}px`;
  } else {
    scaler.style.transformOrigin = 'top left';
    scaler.style.margin = '0 auto';
    scaler.style.transform = 'none';
    container.style.height = 'auto';
  }
}
window.addEventListener('resize', scaleIntro);
window.addEventListener('load', scaleIntro);

// DOM読み込み後の初期化処理
document.addEventListener('DOMContentLoaded', () => {
  // コンタクトフォーム送信処理
  const form = document.getElementById('contactForm');
  if(form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const btn = document.getElementById('submitBtn');
      const msg = document.getElementById('formMsg');

      btn.textContent = '送信中...';
      btn.disabled = true;
      msg.style.display = 'none';

      const formData = new FormData(this);

      fetch('contact.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
          if (data.ok) {
            msg.textContent = 'お問い合わせを受け付けました。後ほどご連絡いたします。';
            msg.style.cssText = 'display:block; color:#2ecc71; margin-top:16px; font-size:13px;';
            this.reset();
          } else {
            throw new Error(data.error || '送信エラー');
          }
        })
        .catch(err => {
          msg.textContent = '送信に失敗しました。時間をおいて再度お試しください。';
          msg.style.cssText = 'display:block; color:#e74c3c; margin-top:16px; font-size:13px;';
          console.error(err);
        })
        .finally(() => {
          btn.textContent = '送信';
          btn.disabled = false;
        });
    });
  }

  // キャッシュバスティング用のクエリ
  const imgVer = new Date().getTime();

  // 最新Information (WORKS) の取得
  fetch('data/works.json?t=' + Date.now())
    .then(r => r.json())
    .then(data => {
      const table = document.getElementById('topInfoTable');
      if (!table) return;
      // 最新の3件を表示
      const latest = data.slice(0, 3);
      table.innerHTML = latest.map(item => `
        <tr>
          <td class="info-date">${item.date}</td>
          <td class="info-tag"><span>${item.category.toUpperCase()}</span></td>
          <td class="info-content"><a href="worksdetail.html?id=${item.id}" style="color:var(--text);">${item.title}</a><span class="info-excerpt"><br><small>${item.excerpt}</small></span></td>
        </tr>
      `).join('');
    })
    .catch(err => console.error('Works Load Error:', err));

  // トップGallery の取得
  fetch('data/gallery.json?t=' + Date.now())
    .then(r => r.json())
    .then(data => {
      const grid = document.getElementById('topGalleryGrid');
      if (!grid) return;
      
      const items = data.slice(0, 5);
      const cells = items.map((item, i) => {
        // 画像が切れないよう object-fit: contain を指定
        const imgHtml = item.mainImage 
          ? `<img src="${item.mainImage}?v=${imgVer}" alt="${item.alt || item.title}" style="width:100%;height:100%;object-fit:contain;" loading="lazy">`
          : `<div class="gallery-placeholder">PHOTO ${String(i+1).padStart(2,'0')}</div>`;
        return `<a href="gallery.html#work-${item.id}" class="gallery-cell">${imgHtml}</a>`;
      });
      while(cells.length < 5) {
        cells.push(`<div class="gallery-cell"><div class="gallery-placeholder">PHOTO ${String(cells.length+1).padStart(2,'0')}</div></div>`);
      }
      grid.innerHTML = cells.join('');
    })
    .catch(err => console.error('Gallery Load Error:', err));
});
