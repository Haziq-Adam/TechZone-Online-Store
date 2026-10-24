document.addEventListener('DOMContentLoaded', () => {
    // Animated counter for stats
    document.querySelectorAll('.stat-num').forEach(el => {
        const text = el.textContent;
        const num = parseInt(text.replace(/\D/g, ''));
        if(!num) return;
        let start = 0;
        const duration = 1500;
        const step = Math.ceil(num / (duration / 16));
        const suffix = text.replace(/[\d,]/g, '');
        const timer = setInterval(() => {
            start = Math.min(start + step, num);
            el.textContent = start.toLocaleString() + suffix;
            if(start >= num) clearInterval(timer);
        }, 16);
    });

    // Flash message on cart add
    if(window.location.search.includes('added=1')){
        const toast = document.createElement('div');
        toast.innerHTML = '🛒 Added to cart!';
        toast.style.cssText = 'position:fixed;bottom:80px;right:30px;background:linear-gradient(135deg,#6c63ff,#ff6584);color:#fff;padding:12px 24px;border-radius:12px;font-weight:600;z-index:9999;animation:fadeUp .4s ease;box-shadow:0 8px 20px rgba(108,99,255,.4)';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }

    // Smooth reveal on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if(e.isIntersecting){
                e.target.style.animation = 'fadeUp .6s ease both';
                observer.unobserve(e.target);
            }
        });
    }, {threshold: 0.1});
    document.querySelectorAll('.product-card, .testimonial-card, .admin-card').forEach(el => observer.observe(el));
});
