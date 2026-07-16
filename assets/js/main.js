/**
 * Atlas Centro Sul — JavaScript Principal v2
 */

// ── NAVBAR SCROLL (glass effect) ─────────────────────────
const navbar = document.getElementById('navbar');
if (navbar) {
    const updateNav = () => {
        if (window.scrollY > 40) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    };
    window.addEventListener('scroll', updateNav, { passive: true });
    updateNav();
}

// ── MOBILE MENU ──────────────────────────────────────────
const navToggle = document.getElementById('navToggle');
const navLinks  = document.getElementById('navLinks');

if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
        navLinks.classList.toggle('open');
        const isOpen = navLinks.classList.contains('open');
        navToggle.setAttribute('aria-expanded', isOpen);
        const spans = navToggle.querySelectorAll('span');
        if (isOpen) {
            spans[0].style.transform = 'rotate(45deg) translateY(7px)';
            spans[1].style.opacity   = '0';
            spans[1].style.transform = 'scaleX(0)';
            spans[2].style.transform = 'rotate(-45deg) translateY(-7px)';
        } else {
            spans[0].style.transform = '';
            spans[1].style.opacity   = '';
            spans[1].style.transform = '';
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
            spans[1].style.transform = '';
            spans[2].style.transform = '';
        }
    });
}

// ── SCROLL REVEAL (IntersectionObserver) ─────────────────
const revealTargets = document.querySelectorAll(
    '.pilar-card, .eco-card, .news-card, .news-carousel-wrapper, ' +
    '.feature-card, .value-card, .gallery-item, .contact-info-card, ' +
    '.about-grid, .stat-item'
);

if (revealTargets.length) {
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                // Delay escalonado para cards em grid
                const delay = entry.target.dataset.revealDelay || 0;
                setTimeout(() => {
                    entry.target.classList.add('visible');
                    entry.target.style.opacity  = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, delay);
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -32px 0px' });

    revealTargets.forEach((el, index) => {
        // Só animar se não estiver já visível (acima do fold)
        const rect = el.getBoundingClientRect();
        if (rect.top > window.innerHeight) {
            el.style.opacity   = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.55s cubic-bezier(.16,1,.3,1), transform 0.55s cubic-bezier(.16,1,.3,1)';
            // Delay escalonado para cards num mesmo grid
            const siblings = el.parentElement ? el.parentElement.children : [];
            const siblingIndex = Array.from(siblings).indexOf(el);
            if (siblingIndex >= 0 && siblingIndex < 6) {
                el.dataset.revealDelay = siblingIndex * 80;
            }
            revealObserver.observe(el);
        }
    });
}

// ── VIDEO MODAL ──────────────────────────────────────────
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

const videoModal = document.getElementById('videoModal');
if (videoModal) {
    videoModal.addEventListener('click', (e) => {
        if (e.target === videoModal) fecharVideo();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') fecharVideo();
    });
}

// ── SMOOTH COUNTER ────────────────────────────────────────
function animarContador(el) {
    const target = parseFloat(el.dataset.target || el.textContent);
    if (isNaN(target) || target > 10000) return;
    let current = 0;
    const step  = Math.max(target / 40, 1);
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            el.textContent = el.dataset.suffix ? target + el.dataset.suffix : target;
            clearInterval(timer);
        } else {
            el.textContent = Math.ceil(current);
        }
    }, 40);
}

const counters = document.querySelectorAll('.counter');
if (counters.length) {
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                animarContador(e.target);
                counterObserver.unobserve(e.target);
            }
        });
    }, { threshold: 0.5 });
    counters.forEach(el => counterObserver.observe(el));
}

// ── FLASH MESSAGE AUTO-DISMISS ────────────────────────────
const alerts = document.querySelectorAll('.alert');
alerts.forEach(alert => {
    setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-8px)';
        setTimeout(() => alert.remove(), 500);
    }, 5000);
});

// ── FORM SUBMIT LOADING ───────────────────────────────────
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function () {
        const btn = this.querySelector('[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.textContent = '⏳ A processar...';
        }
    });
});
