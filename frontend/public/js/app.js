// ========== FITNESS VALS — APP.JS ==========
document.addEventListener('DOMContentLoaded', function() {

    // ── NAVBAR SCROLL EFFECT ──
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });
    }

    // ── MOBILE NAV TOGGLE ──
    const mobileToggle = document.getElementById('mobileToggle');
    const navLinks = document.getElementById('navLinks');
    if (mobileToggle && navLinks) {
        mobileToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
            const spans = mobileToggle.querySelectorAll('span');
            mobileToggle.classList.toggle('active');
        });
    }

    // ── DASHBOARD SIDEBAR TOGGLE ──
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('dashSidebar');
    if (sidebarToggle && sidebar) {
        const updateToggle = () => {
            sidebarToggle.style.display = window.innerWidth <= 768 ? 'flex' : 'none';
        };
        updateToggle();
        sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
        window.addEventListener('resize', updateToggle);
    }

    // ── COUNTER ANIMATION ──
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length) {
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = el.getAttribute('data-count');
                    const suffix = el.getAttribute('data-suffix') || '';
                    const prefix = el.getAttribute('data-prefix') || '';
                    const num = parseInt(target.replace(/[^0-9]/g, ''));
                    let current = 0;
                    const step = Math.ceil(num / 90);
                    const timer = setInterval(() => {
                        current += step;
                        if (current >= num) { current = num; clearInterval(timer); }
                        el.textContent = prefix + current.toLocaleString() + suffix;
                    }, 16);
                    counterObserver.unobserve(el);
                }
            });
        }, { threshold: 0.3 });
        counters.forEach(c => counterObserver.observe(c));
    }

    // ── FAQ ACCORDION ──
    document.querySelectorAll('.faq-question').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.faq-item');
            const isActive = item.classList.contains('active');
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
            if (!isActive) item.classList.add('active');
        });
    });

    // ── TAB SYSTEM ──
    document.querySelectorAll('.tab-btn').forEach(tab => {
        tab.addEventListener('click', () => {
            const group = tab.closest('.tabs') || tab.parentElement;
            group.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const target = tab.getAttribute('data-tab');
            if (target) {
                document.querySelectorAll('.tab-content').forEach(tc => tc.style.display = 'none');
                const panel = document.getElementById(target);
                if (panel) panel.style.display = 'block';
            }
        });
    });

    // ── MODAL SYSTEM ──
    document.querySelectorAll('[data-modal]').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = document.getElementById(trigger.getAttribute('data-modal'));
            if (modal) { modal.classList.add('active'); document.body.style.overflow = 'hidden'; }
        });
    });
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) { overlay.classList.remove('active'); document.body.style.overflow = ''; }
        });
    });
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.modal-overlay').classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    // ── TOGGLE SWITCHES ──
    document.querySelectorAll('.toggle input').forEach(toggle => {
        toggle.addEventListener('change', () => {
            const label = toggle.closest('.feature-toggle-item');
            if (label) label.style.borderColor = toggle.checked ? 'var(--color-accent)' : '';
        });
    });

    // ── SMOOTH SCROLL ──
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
    });
});
