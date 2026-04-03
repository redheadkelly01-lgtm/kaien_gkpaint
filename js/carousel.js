/* js/carousel.js - Gallery Carousel Control */
document.addEventListener('DOMContentLoaded', () => {
  const carousel = document.getElementById('topGalleryCarousel');
  const prevBtn = document.getElementById('galleryPrev');
  const nextBtn = document.getElementById('galleryNext');
  
  if (!carousel) return;

  const imgVer = new Date().getTime();

  // ギャラリーデータの取得
  fetch('data/gallery.json?t=' + Date.now())
    .then(r => r.json())
    .then(data => {
      if (!data || data.length === 0) {
        carousel.innerHTML = '<div style="color:var(--text-dim); padding:40px; text-align:center; width:100%;">NO WORKS IN GALLERY</div>';
        return;
      }

      // 全データをカルーセル用アイテムとして生成
      carousel.innerHTML = data.map((item, i) => {
        const imgHtml = item.mainImage 
          ? `<img src="${item.mainImage}?v=${imgVer}" alt="${item.alt || item.title}" loading="lazy">`
          : `<div class="gallery-placeholder">PHOTO ${String(i+1).padStart(2,'0')}</div>`;
          
        return `
          <article class="gallery-item">
            <a href="gallery.html#work-${item.id}" class="gallery-cell">
              ${imgHtml}
              <div class="gallery-overlay">
                <span class="gallery-title">${item.title}</span>
              </div>
            </a>
          </article>`;
      }).join('');
      
      // ナビゲーションの制御
      const scrollAmount = () => {
        const firstItem = carousel.querySelector('.gallery-item');
        if (!firstItem) return 300;
        // ギャップを含めた1枚分の幅を計算
        const style = window.getComputedStyle(carousel);
        const gap = parseInt(style.gap) || 16;
        return firstItem.offsetWidth + gap;
      };

      if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
          carousel.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
        });
        nextBtn.addEventListener('click', () => {
          carousel.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
        });
      }

      // ボタンの表示/非表示（端に到達したときなど、必要であれば実装可能だが、Snap Scrollがあるため基本不要）
    })
    .catch(err => {
      console.error('Gallery Carousel Load Error:', err);
      carousel.innerHTML = '<div style="color:var(--accent2); padding:40px; text-align:center; width:100%;">ERROR LOADING GALLERY</div>';
    });
});
