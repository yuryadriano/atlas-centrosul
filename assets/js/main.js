/**
 * Atlas Centro Sul — JavaScript Principal
 */

// ── NAVBAR SCROLL ────────────────────────────────
const navbar = document.getElementById('navbar');
if (navbar) {
    const updateNav = () => {
        if (window.scrollY > 60) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    };
    window.addEventListener('scroll', updateNav, { passive: true });
    updateNav();
}

// ── MOBILE MENU ──────────────────────────────────
const navToggle = document.getElementById('navToggle');
const navLinks  = document.getElementById('navLinks');

if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
        navLinks.classList.toggle('open');
        const isOpen = navLinks.classList.contains('open');
        navToggle.setAttribute('aria-expanded', isOpen);
        // Animar hamburger
        const spans = navToggle.querySelectorAll('span');
        if (isOpen) {
            spans[0].style.transform = 'rotate(45deg) translateY(7px)';
            spans[1].style.opacity   = '0';
            spans[2].style.transform = 'rotate(-45deg) translateY(-7px)';
        } else {
            spans[0].style.transform = '';
            spans[1].style.opacity   = '';
            spans[2].style.transform = '';
        }
    });

    // Fechar ao clicar fora
    document.addEventListener('click', (e) => {
        if (!navbar.contains(e.target) && navLinks.classList.contains('open')) {
            navLinks.classList.remove('open');
            const spans = navToggle.querySelectorAll('span');
            spans[0].style.transform = '';
            spans[1].style.opacity   = '';
            spans[2].style.transform = '';
        }
    });
}

// ── SCROLL REVEAL ────────────────────────────────
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity  = '1';
            entry.target.style.transform = 'translateY(0)';
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.eco-card, .news-carousel-wrapper, .news-grid, .feature-card, .value-card, .gallery-item').forEach(el => {
    el.style.opacity   = '0';
    el.style.transform = 'translateY(24px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    revealObserver.observe(el);
});

// ── VIDEO MODAL ──────────────────────────────────
function abrirVideo(url, titulo) {
    const modal = document.getElementById('videoModal');
    const frame = document.getElementById('videoFrame');
    if (modal && frame) {
        frame.src = url + '?autoplay=1';
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function fecharVideo() {
    const modal = document.getElementById('videoModal');
    const frame = document.getElementById('videoFrame');
    if (modal && frame) {
        frame.src = '';
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Fechar modal ao clicar fora do iframe
const videoModal = document.getElementById('videoModal');
if (videoModal) {
    videoModal.addEventListener('click', (e) => {
        if (e.target === videoModal) fecharVideo();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') fecharVideo();
    });
}

// ── SMOOTH COUNTER (números do hero) ─────────────
function animarContador(el) {
    const target = parseFloat(el.textContent);
    if (isNaN(target) || target > 100) return;
    let current = 0;
    const step  = target / 40;
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            el.textContent = el.dataset.suffix ? target + el.dataset.suffix : target + '+';
            clearInterval(timer);
        } else {
            el.textContent = Math.ceil(current) + (target < 100 ? '+' : '');
        }
    }, 40);
}

const heroStats = document.querySelectorAll('.hero-stat-num');
const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            animarContador(e.target);
            statsObserver.unobserve(e.target);
        }
    });
}, { threshold: 0.5 });
heroStats.forEach(el => statsObserver.observe(el));

// ── FLASH MESSAGE AUTO-DISMISS ───────────────────
const alerts = document.querySelectorAll('.alert');
alerts.forEach(alert => {
    setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }, 5000);
});

// ── FORM SUBMIT LOADING ──────────────────────────
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function () {
        const btn = this.querySelector('[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.textContent = '⏳ A processar...';
        }
    });
});
