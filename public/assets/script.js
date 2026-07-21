let cart = JSON.parse(localStorage.getItem('patisserie_cart')) || [];

// Робимо функцію глобальною, щоб кнопки "+" та "-" всередині кошика працювали
window.changeQty = function(index, delta) {
    cart[index].quantity += delta;
    if(cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }
    saveCart();
}

function saveCart() {
    localStorage.setItem('patisserie_cart', JSON.stringify(cart));
    updateCartUI();
}

function updateCartUI() {
    const cartItemsContainer = document.getElementById('cart-items-container');
    const cartTotalPrice = document.getElementById('cart-total-price');
    const cartBadgeCount = document.getElementById('cart-badge-count');
    const cartHiddenInput = document.getElementById('cart-hidden-input');

    if (!cartItemsContainer) return;
    
    cartItemsContainer.innerHTML = '';
    let total = 0;
    let count = 0;

    cart.forEach((item, index) => {
        total += item.price * item.quantity;
        count += item.quantity;

        const itemEl = document.createElement('div');
        itemEl.style.display = 'flex';
        itemEl.style.justify = 'space-between';
        itemEl.style.alignItems = 'center';
        itemEl.style.marginBottom = '1rem';
        itemEl.style.fontSize = '0.9rem';
        itemEl.innerHTML = `
            <div>
                <div style="font-weight:500;">${item.name}</div>
                <div style="color:#888; font-size:0.8rem;">${item.price} грн x ${item.quantity}</div>
            </div>
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <button onclick="changeQty(${index}, -1)" style="border:1px solid #ddd; background:#fff; cursor:pointer; padding:2px 6px;">-</button>
                <span>${item.quantity}</span>
                <button onclick="changeQty(${index}, 1)" style="border:1px solid #ddd; background:#fff; cursor:pointer; padding:2px 6px;">+</button>
            </div>
        `;
        cartItemsContainer.appendChild(itemEl);
    });

    if (cartTotalPrice) cartTotalPrice.innerText = total.toFixed(2) + ' грн';
    if (cartBadgeCount) cartBadgeCount.innerText = count;
    if (cartHiddenInput) cartHiddenInput.value = JSON.stringify(cart);
}

// Навішуємо події після повного завантаження DOM дерева сторінки
document.addEventListener('DOMContentLoaded', () => {
    const cartBtn = document.getElementById('cart-btn');
    const closeCartBtn = document.getElementById('close-cart');
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartOverlay = document.getElementById('cart-overlay');

    if (cartBtn && cartSidebar && cartOverlay) {
        cartBtn.addEventListener('click', () => {
            cartSidebar.classList.add('open');
            cartOverlay.classList.add('show');
        });
    }

    if (closeCartBtn && cartSidebar && cartOverlay) {
        closeCartBtn.addEventListener('click', () => {
            cartSidebar.classList.remove('open');
            cartOverlay.classList.remove('show');
        });
        cartOverlay.addEventListener('click', () => {
            cartSidebar.classList.remove('open');
            cartOverlay.classList.remove('show');
        });
    }

    // Слухач для кнопок "в кошик" за допомогою делегування подій
    document.body.addEventListener('click', (e) => {
        if (e.target && e.target.classList.contains('btn-card-buy')) {
            const button = e.target;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = parseFloat(button.getAttribute('data-price'));

            const existingItem = cart.find(item => item.id === id);
            if(existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ id, name, price, quantity: 1 });
            }
            saveCart();
            
            if (cartSidebar && cartOverlay) {
                cartSidebar.classList.add('open');
                cartOverlay.classList.add('show');
            }
        }
    });

    updateCartUI();
});

// --- Логіка швидкого сортування без перезавантаження сторінки ---
const dropdownMenu = document.getElementById('sort-dropdown-menu');
const sortTriggerText = document.getElementById('sort-trigger-text');
const productsGrid = document.querySelector('.products-grid');

if (dropdownMenu && productsGrid) {
    // 💾 Запам'ятовуємо точний початковий порядок карток, як вони прийшли з БД Laravel
    const originalOrder = Array.from(productsGrid.querySelectorAll('.product-card'));

    dropdownMenu.addEventListener('click', (e) => {
        e.preventDefault();
        
        const target = e.target.closest('a');
        if (!target) return;

        const sortType = target.getAttribute('data-sort');

        // Оновлюємо активний клас у випадаючому списку
        dropdownMenu.querySelectorAll('a').forEach(a => a.classList.remove('selected'));
        target.classList.add('selected');
        
        // Оновлюємо текст на головній кнопці сортування
        if (sortTriggerText) {
            sortTriggerText.innerText = target.innerText;
        }

        // Якщо обрано дефолт — просто беремо наш збережений оригінальний масив з БД
        if (sortType === 'default') {
            productsGrid.innerHTML = '';
            originalOrder.forEach(product => productsGrid.appendChild(product));
            return; // Виходимо, далі сортувати не потрібно
        }

        // Для інших типів — беремо поточні картки й сортуємо
        const products = Array.from(productsGrid.querySelectorAll('.product-card'));

        products.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('.price').innerText.replace(/[^0-9.]/g, ''));
            const priceB = parseFloat(b.querySelector('.price').innerText.replace(/[^0-9.]/g, ''));
            
            const nameA = a.querySelector('h3').innerText.trim().toLowerCase();
            const nameB = b.querySelector('h3').innerText.trim().toLowerCase();

            switch (sortType) {
                case 'price_asc':
                    return priceA - priceB;
                case 'price_desc':
                    return priceB - priceA;
                case 'name_asc':
                    return nameA.localeCompare(nameB, 'uk');
                case 'name_desc':
                    return nameB.localeCompare(nameA, 'uk');
            }
        });

        // Очищаємо сітку та вставляємо відсортовані картки
        productsGrid.innerHTML = '';
        products.forEach(product => productsGrid.appendChild(product));
    });
}