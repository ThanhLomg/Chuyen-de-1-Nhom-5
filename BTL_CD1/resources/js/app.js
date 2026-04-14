import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ============================================
// Global function: Thêm vào giỏ hàng (AJAX)
// ============================================
window.addToCart = async function(productId, quantity = 1) {
    try {
        const res = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity }),
        });
        
        const data = await res.json();
        
        if (data.success) {
            // Cập nhật số lượng giỏ hàng trên tất cả các badge
            document.querySelectorAll('.cart-count').forEach(el => {
                el.textContent = data.count;
            });
            showToast(data.message || 'Đã thêm vào giỏ hàng', 'success');
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
        }
    } catch (error) {
        console.error('Add to cart error:', error);
        showToast('Không thể kết nối đến máy chủ', 'error');
    }
};

// ============================================
// Toast notification
// ============================================
window.showToast = function(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 z-50 px-4 py-3 rounded shadow-lg text-white text-sm font-medium transition-all transform ${
        type === 'success' ? 'bg-green-600' : 'bg-red-500'
    }`;
    toast.style.animation = 'slideIn 0.3s ease-out';
    toast.textContent = message;
    
    // Thêm style animation nếu chưa có
    if (!document.getElementById('toast-animation')) {
        const style = document.createElement('style');
        style.id = 'toast-animation';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = 'all 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
};

// ============================================
// Search Autocomplete
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    if (!searchInput) return;
    
    const resultsBox = document.getElementById('search-results');
    if (!resultsBox) return;
    
    let debounceTimer;
    let abortController = null;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        
        const query = this.value.trim();
        
        // Hủy request trước đó nếu có
        if (abortController) {
            abortController.abort();
        }
        
        // Ẩn kết quả nếu query quá ngắn
        if (query.length < 2) {
            resultsBox.innerHTML = '';
            resultsBox.classList.add('hidden');
            return;
        }
        
        debounceTimer = setTimeout(async () => {
            try {
                abortController = new AbortController();
                
                const res = await fetch(`/search?q=${encodeURIComponent(query)}`, {
                    headers: { 'Accept': 'application/json' },
                    signal: abortController.signal
                });
                
                const items = await res.json();
                
                if (!items.length) {
                    resultsBox.innerHTML = `
                        <div class="p-4 text-center text-gray-500">
                            Không tìm thấy sản phẩm nào
                        </div>
                    `;
                    resultsBox.classList.remove('hidden');
                    return;
                }
                
                resultsBox.innerHTML = items.map(item => `
                    <a href="${item.url}" class="flex items-center gap-3 p-3 hover:bg-gray-50 border-b last:border-0 transition">
                        <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 line-clamp-1">${item.name}</p>
                            <p class="text-sm text-primary font-semibold">${item.price}</p>
                        </div>
                    </a>
                `).join('');
                
                resultsBox.classList.remove('hidden');
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error('Search error:', error);
                }
            }
        }, 300);
    });
    
    // Ẩn kết quả khi click ra ngoài
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.classList.add('hidden');
        }
    });
    
    // Hiển thị lại nếu input đang có giá trị và được focus
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2 && resultsBox.children.length > 0) {
            resultsBox.classList.remove('hidden');
        }
    });
    
    // Điều hướng bằng phím
    let selectedIndex = -1;
    searchInput.addEventListener('keydown', function(e) {
        const items = resultsBox.querySelectorAll('a');
        if (!items.length) return;
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
            updateSelection(items, selectedIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = Math.max(selectedIndex - 1, -1);
            updateSelection(items, selectedIndex);
        } else if (e.key === 'Enter') {
            if (selectedIndex >= 0) {
                e.preventDefault();
                items[selectedIndex].click();
            }
        } else if (e.key === 'Escape') {
            resultsBox.classList.add('hidden');
            selectedIndex = -1;
        }
    });
    
    function updateSelection(items, index) {
        items.forEach((item, i) => {
            if (i === index) {
                item.classList.add('bg-gray-100');
                item.focus();
            } else {
                item.classList.remove('bg-gray-100');
            }
        });
    }
});