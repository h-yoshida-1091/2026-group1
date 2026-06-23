document.addEventListener('DOMContentLoaded', function() {
    // 1. お気に入りボタンの制御
    const favoriteButtons = document.querySelectorAll('.favorite-btn');
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const isFavorited = this.classList.contains('favorited');
            const url = isFavorited ? '/products/unfavorite' : '/products/favorite';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => {
                if (response.status === 401) {
                    location.href = '/login';
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (!data || data.status !== 'success') return;

                if (isFavorited) {
                    this.classList.remove('favorited');
                    this.textContent = '♡';
                } else {
                    this.classList.add('favorited');
                    this.textContent = '♥';
                }
            })
            .catch(error => console.error(error));
        });
    });

    // 2. 画面読み込み時に価格スライダーを初期化
    if (document.getElementById('inputMinPrice')) {
        updateSlider();
    }
});

// 並び替えフォームの変更時
function changeSort(sortValue) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', sortValue);
    window.location.href = url.toString();
}

// 特定のタグの「×」を押した時
function removeParam(...paramNames) {
    const url = new URL(window.location.href);
    paramNames.forEach(param => url.searchParams.delete(param));
    window.location.href = url.toString();
}

// すべての絞り込みを一括解除
function clearAllFilters() {
    const url = new URL(window.location.href);
    const sort = url.searchParams.get('sort');
    url.search = '';
    if (sort) url.searchParams.set('sort', sort);
    window.location.href = url.toString();
}

// ダブルスライダーの表示とツマミの交差制限
function updateSlider() {
    const minInput = document.getElementById('inputMinPrice');
    const maxInput = document.getElementById('inputMaxPrice');
    const labelMin = document.getElementById('labelMinPrice');
    const labelMax = document.getElementById('labelMaxPrice');
    const track = document.querySelector('.slider-track');

    if (!minInput || !maxInput) return;

    let minVal = parseInt(minInput.value);
    let maxVal = parseInt(maxInput.value);

    // つまみが重なった時に最低100円の間隔をキープ（交差防止）
    if (maxVal - minVal < 100) {
        if (document.activeElement === minInput) {
            minInput.value = maxVal - 100;
            minVal = maxVal - 100;
        } else {
            maxInput.value = minVal + 100;
            maxVal = minVal + 100;
        }
    }

    // 表示金額の更新
    labelMin.textContent = '¥' + minVal.toLocaleString();
    labelMax.textContent = '¥' + maxVal.toLocaleString();

    // スライダーの間のゲージに青色を塗る計算
    const minPercent = ((minVal - minInput.min) / (minInput.max - minInput.min)) * 100;
    const maxPercent = ((maxVal - maxInput.min) / (maxInput.max - maxInput.min)) * 100;
    track.style.background = `linear-gradient(to right, #ddd ${minPercent}%, #0066cc ${minPercent}%, #0066cc ${maxPercent}%, #ddd ${maxPercent}%)`;
}

// スライダーの値をURLに適用して検索
function applyPriceFilter() {
    const minVal = document.getElementById('inputMinPrice').value;
    const maxVal = document.getElementById('inputMaxPrice').value;
    
    const url = new URL(window.location.href);
    url.searchParams.set('min_price', minVal);
    url.searchParams.set('max_price', maxVal);
    window.location.href = url.toString();
}

// 動的価格帯リストのリンクがタップされた時
function clickPriceRange(min, max) {
    document.getElementById('inputMinPrice').value = min;
    document.getElementById('inputMaxPrice').value = max;
    updateSlider();
    applyPriceFilter();
}