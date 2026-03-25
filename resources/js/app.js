import './bootstrap';
import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Flash message auto-hide
document.addEventListener('DOMContentLoaded', () => {
    const flash = document.getElementById('flash-message');
    if (flash) setTimeout(() => flash.classList.add('opacity-0'), 3000);

    // Keranjang badge update
    document.querySelectorAll('[data-add-cart]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const res = await fetch(btn.dataset.addCart, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            });
            const data = await res.json();
            document.querySelectorAll('[data-cart-count]').forEach(el => el.textContent = data.count);
        });
    });
});
